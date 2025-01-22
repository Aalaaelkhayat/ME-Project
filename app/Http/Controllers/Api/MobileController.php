<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\MobileResource;
use App\Models\Mobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MobileController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'user_id'=>'required',
            'address_id'=>'required',
            'number'=>'required',
            'type'=>'required',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $mobile=Mobile::create($request->all());
        if($mobile){
            return $this->apiResponse(new MobileResource($mobile),'Mobile has been created successfully',201);
        }
        else{
            return $this->apiResponse(null,'Bad Request',400);
        }
    }
    public function update(Request $request,$id)
    {
        $mobile=Mobile::find($id);
        if(!$mobile)
        {
            return $this->apiResponse(null,'Mobile not found',404);
        }
        $validator=Validator::make($request->all(),[
            'number'=>'required',
            'type'=>'required',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $mobile->update($request->all());
        if($mobile)
        {
            return $this->apiResponse(new MobileResource($mobile),'Mobile has been updated',201);
        }
    }
    public function destroy($id)
    {
        $mobile=Mobile::find($id);
        if(!$mobile)
        {
            return $this->apiResponse(null,'Mobile Not Found',404);
        }
        $mobile->delete();
        if($mobile)
        {
            return $this->apiResponse(null,'Mobile has been deleted',200);
        }
    }
}
