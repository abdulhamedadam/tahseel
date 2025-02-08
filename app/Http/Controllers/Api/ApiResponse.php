<?php


namespace App\Http\Controllers\Api;


trait ApiResponse
{
    function responseApi($content = null, $massage = null, $status = null)
    {
        $array = ['data' => $content,
            'message' => $massage,
            'status' => $status
        ];
        return response()->json($array, $status);
    }

    function responseApiError($massage = null, $status = null)
    {


        $array = ['message' => $massage,
            'status' => $status
        ];
        return response($array, $status);
    }



}
