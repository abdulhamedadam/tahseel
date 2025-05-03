<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MasrofatResource;
use App\Models\Admin\Masrofat;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class MasrofatController extends Controller
{
    use ResponseApi;

    public function index(Request $request)
    {
        try{
            $query = Masrofat::with(['employee', 'sarf_band', 'user'])
                ->orderBy('created_at', 'desc');

            if ($request->has('from_date') && $request->from_date != '') {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->has('to_date') && $request->to_date != '') {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            if ($request->has('month') && $request->month != '') {
                $query->whereMonth('created_at', $request->month);
            }

            if ($request->has('year') && $request->year != '') {
                $query->whereYear('created_at', $request->year);
            }

            $masrofat = $query->get();
            $total=$masrofat->sum('value');

            $masrofat = MasrofatResource::collection($masrofat);
            return $this->responseApi_v2($masrofat, 'تم استرجاع المصروفات بنجاح',true,$total);
        } catch (\Exception $e) {
            return $this->responseApiError('حدث خطأ ما.');
        }
    }
}
