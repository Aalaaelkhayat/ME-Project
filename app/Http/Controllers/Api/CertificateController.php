<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'user_id'=>'required',
            'title'=>'required',
            'body'=>'required',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $certificate=Certificate::create($request->all());
        if($certificate)
        {
            return $this->apiResponse(new CertificateResource($certificate),'Certificate has created successfully',201);
        }
        else
        {
            return $this->apiResponse(null,'Bad Request',400);
        }
    }
    public function update(Request $request,$id)
    {
        $certificate=Certificate::find($id);
        if(!$certificate)
        {
            return $this->apiResponse(null,'Certificate not found',404);
        }
        $validator=Validator::make($request->all(),[
            // 'user_id'=>'required',
            'title'=>'required',
            'body'=>'required',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $certificate->update($request->all());
        if($certificate)
        {
            return $this->apiResponse(new CertificateResource($certificate),'Certificate has been updated successfully',201);
        }
        else
        {
            return $this->apiResponse(null,'Bad Request',400);
        }
    }
    public function destroy($id)
    {
        $certificate=Certificate::find($id);
        if(!$certificate)
        {
            return $this->apiResponse(null,'Certificate not found',404);
        }
        $certificate->delete();
        if($certificate)
        {
            return $this->apiResponse(null,'Certificate has been deleted successfully',200);
        }
    }
    public function getUserCertificates($user_id)
    {
        $user_cetificates=CertificateResource::collection(Certificate::where('user_id', $user_id)->get());
        // dd($user_cetificates);
        if($user_cetificates)
        {
            return $this->apiResponse($user_cetificates,'User certificates records has been fetched successfully',200);
        }
        else
        {
            return $this->apiResponse(null,'User not found',404);
        }
    }
}
