<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

trait ApiResponseTrait
{
    //
    public function apiResponse($data=null,$message=null,$status=null)
    {
        $array=[
            'status'=>$status,
            'message'=>$message,
            'data'=>$data,
        ];
        return response()->json($array);
    }
}
