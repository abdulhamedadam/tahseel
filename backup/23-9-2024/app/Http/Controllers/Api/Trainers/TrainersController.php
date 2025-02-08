<?php

namespace App\Http\Controllers\Api\Trainers;

use App\Http\Controllers\Api\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\subscriptions\TrainerResource;
use App\Models\Trainers;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

class TrainersController extends Controller
{
    use ImageProcessing;
    use ApiResponse;
    use ValidationMessage;

    public function login_user(Request $request)
    {

        try {
            $validated = $request->validate([
                'login' => 'required|string', // This can be email, username, or phone
                'password' => 'required|string|min:6',
            ]);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $erros = $this->customErrorRespons($exception->errors());
            $list_error = $erros['list_error'];
            $list_error_string = $erros['list_error_string'];
            return $this->responseApi($list_error, $list_error_string, 422);
            /*            return $this->responseApiError($exception->errors(), 422);*/
        };
//dd($request->all());
        $login = $validated['login'];
        $password = $validated['password'];
        $field = 'user_name';

        /*// Determine the field type
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
            // } elseif (preg_match('/^\d{11}$/', $login)) { // Assuming phone number is 10 digits
        } elseif (preg_match('/^\d{7,}$/', $login)) { // Match any phone number with at least 7 digits

            $field = 'phone';
        } else {
            $field = 'user_name';
        }*/
        if (!$token = auth('trainer')->attempt([$field => $login, 'password' => $password])) {
            return $this->responseApi(null, 'غير مسموح ', 401);

        }
        Trainers::find(auth('trainer')->user()->id)->update(['tokenNoti' => $request['tokenNoti'], 'lang' => $request['lang'], 'extradata' => $request['password']]);

        return $this->createNewToken($token);
    }

    /************************************************/
    public function show(Request $request)
    {
        try {
            if (auth('trainer')->check()) {

                $user_id = $request->user_id;
                $user = Trainers::find($user_id);

                if ($user) {
                    $user = new TrainerResource($user);

                    return $this->responseApi($user, 'المستخدم موجود', 200);
                } else {
                    return $this->responseApi(null, 'لم يتم تسجيل المستخدم', 205);
                }
            } else {

                return $this->responseApi(null, trans('api.user_unautherized'), 401);
            }
        } catch (MethodNotAllowedHttpException $e) {
            return $this->responseApiError('Method not allowed.', 405);
        }
    }
    protected function createNewToken($token)
    {
        $data = new TrainerResource(auth('trainer')->user());

        return $this->responseApi([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('trainer')->factory()->getTTL() * 60,
            'user_data' => $data,

        ], 'بيانات العميل', 200);

    }

    public function update(Request $request)
    {
        $userid = auth('trainer')->user()->id;
        $user = Trainers::find($userid);
        if (!$user) {
            return $this->responseApi(null, 'لم يتم تسجيل المستخدم', 205);
        }

        try {
            /*            $validator = Validator::make($request->all(), [*/
            $validated = $request->validate([
                'user_name' => 'required|max:255|unique:trainers,user_name,' . $userid,
                'phone' => 'required|numeric|unique:trainers,phone,' . $userid,
                'email' => 'required|max:255|email|unique:trainers,email,' . $userid,
                'birthday' => 'required',
                'job_title' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $erros = $this->customErrorRespons($exception->errors());
            $list_error = $erros['list_error'];
            $list_error_string = $erros['list_error_string'];
            return $this->responseApi($list_error, $list_error_string, 422);

        }

        try {
            $user->user_name = $request['user_name'];
            $user->email = $request['email'];
            $user->phone = $request['phone'];
            $user->birthday = $request['birthday'];
            $user->job_title = $request['job_title'];


            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                $dataX = $this->upload_image($file, 'trainers');
                $user['user_image'] = $dataX;
            }
            $user->update();
            if ($user) {
                $user = new TrainerResource($user);
                return $this->responseApi($user, trans('api.save_data_done'), 201);
            } else {
                return $this->responseApi(null, trans('api.data_not_save'), 400);
            }
        } catch (\Exception $e) {
            return $this->responseApiError($e->getMessage(), 500);
        }
    }
    public function logout()
    {
        auth('trainer')->logout();
        return $this->responseApi(null, 'تم تسجيل الخروج ', 200);

    }
    public function refresh()
    {
        return $this->createNewToken(auth('trainer')->refresh());
    }

    public function refreshToken()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            /*            return response()->json(['token' => $newToken]);*/
            return $this->responseApi(['token' => $newToken], 'new token', 200);

        } catch (MethodNotAllowedHttpException $e) {
            return $this->responseApiError('Method not allowed.', 405);
        }
    }

    /****************************************************/

    public function sendCodePhone(Request $request)
    {

        try {
            $validated = $request->validate([
                'phone' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $erros = $this->customErrorRespons($exception->errors());
            $list_error = $erros['list_error'];
            $list_error_string = $erros['list_error_string'];
            return $this->responseApi($list_error, $list_error_string, 422);
        };
        try {
            $user_id = '';
            if ($request['user_id']) {
                $user_id = $request['user_id'];
            }
            $user = Trainers::select('id')->where(['phone' => $request['phone']])->whereNotIn('id', [$user_id])->exists();
            if ($user) {
//                $randomCode = generateRandomCode();
                $uniqueCode = generateUniqueRandomCode('trainers', 'remember_token');

                Trainers::where(['phone' => $request['phone']])->update(['remember_token' => $uniqueCode]);

//                $this->sendCode($request['phone'],$randomCode);
                return $this->responseApi($uniqueCode, trans('api.send_code'), 200);

            }

            return $this->responseApi($user, trans('api.no_data'), 204);

        } catch (\Exception $e) {
            return $this->responseApiError($e->getMessage(), 500);

        }

    }

    public function checkExiteCode(Request $request)
    {

        try {
            $validated = $request->validate([
                'code' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $erros = $this->customErrorRespons($exception->errors());
            $list_error = $erros['list_error'];
            $list_error_string = $erros['list_error_string'];
            return $this->responseApi($list_error, $list_error_string, 422);
        };
        try {
            $user_id = '';
            if ($request['user_id']) {
                $user_id = $request['user_id'];
            }
            $user = Trainers::select('id')->where(['remember_token' => $request['code']])->exists();
            if ($user) {

                return $this->responseApi($user, trans('api.code_exist'), 200);
            }else{
                return $this->responseApi($user, trans('api.code_not_exist'), 204);

            }

        } catch (\Exception $e) {
            return $this->responseApiError($e->getMessage(), 500);

        }

    }

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|confirmed',
            'code' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return $this->responseApi(null, $validator->errors(), 422);

        }

        $input['password'] = Hash::make($request['password']);
        $input['extradata'] = $request['password'];
        try {

            $user = Trainers::where(['remember_token' => $request['code']])->update($input);
            if ($user) {
                return $this->responseApi($user, trans('api.save_data_done'), 202);
            } else {
                return $this->responseApi(null, trans('api.data_not_save'), 422);
            }
        } catch (\Exception $e) {
            return $this->responseApiError($e->getMessage(), 500);

        }
    }

    public function update_lang(Request $request)
    {
        if (auth('trainer')->check()) {

            $userid = auth('trainer')->user()->id;
            $user = Trainers::find($userid);
            $validator = Validator::make($request->all(), [
                'lang' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->responseApi(null, $validator->errors(), 422);

            }
            try {

                $input = $request->all();
//            Userapi::find(auth()->user()->id)->update(['tokenNoti' => $request['tokenNoti'], 'lang' => $request['lang'],'user2' => $request['password']]);
                $user->update($input);
                $user = new TrainerResource($user);
                if ($user) {
                    return $this->responseApi($user, trans('api.save_data_done'), 201);
                } else {
                    return $this->responseApi(null, trans('api.data_not_save'), 400);
                }
            } catch (\Exception $e) {
                return $this->responseApiError($e->getMessage(), 500);

            }
        } else {

            return $this->responseApi(null, trans('api.user_unautherized'), 401);
        }


    }




}
