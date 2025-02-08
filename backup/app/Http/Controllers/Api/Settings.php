<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\subscriptions\MainSubscriptionResource;
use App\Models\City;
use App\Models\District;
use App\Models\GroubTypes;
use App\Models\CarBrand;
use App\Models\subscriptions\MainSubscription_M;
use App\Models\TypesExercises;
use App\Traits\ResponseApi;
use App\Traits\ValidationMessage;
use Illuminate\Http\Request;

class Settings extends Controller
{
    use ResponseApi;
    use ValidationMessage;


    function get_Subscription(Request $request)
    {
        $search = $request->search;

        try {
            if ($search) {
                $data = MainSubscription_M::where('name->' . app()->getLocale(), 'like', "%{$search}%")->get();
            } else {
                $data = MainSubscription_M::all();;
            }

            if (!empty($data)) {
                return $this->ResponseApi(MainSubscriptionResource::collection($data), trans('api.list_TypesExercises'), 200);
            } else {
                return $this->ResponseApi(null, trans('api.nodata'), 204);

            }
        } catch (\Exception $e) {
            return $this->responseApiError($e->getMessage(), 500);

        }
    }

}
