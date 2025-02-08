<?php


namespace App\Traits;


trait ResponseApi
{
    function ResponseApi($content = null, $massage = null, $status = null)
    {

        $array = ['data' => $content,
            'message' => $massage,
            'status' => $status
        ];
        return response($array, $status);

    }

    function responseApiError($massage = null, $status = null)
    {


        $array = ['message' => $massage,
            'status' => $status
        ];
        return response($array, $status);
    }

}
