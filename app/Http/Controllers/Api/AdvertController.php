<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertResource;
use App\Models\Advert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'user_id'=>'required',
            'title'=>'required',
            'body'=>'required',
            // 'date'=>'required|date', //has created dynamically
            'active'=>'required|integer|max:1',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $advert=Advert::create($request->all());
        if($advert)
        {
            return $this->apiResponse(new AdvertResource($advert),'Advert has been created successfully',201);
        }
        else
        {
            return $this->apiResponse('null','Bad request',400);
        }
    }
    public function update(Request $request,$id)
    {
        $advert=Advert::find($id);
        if(!$advert)
        {
            return $this->apiResponse(null,'Advert not found',404);
        }
        $validator=
        Validator::make($request->all(), [
            // 'user_id'=>'required',
            'title'=>'required',
            'body'=>'required',
            // 'date'=>'required|date', //has created dynamically
            'active'=>'required|integer|max:1',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $advert->update($request->all());
        if($advert)
        {
            return $this->apiResponse(new AdvertResource($advert),'Advert has been updated successfully',201);
        }
    }
    public function destroy($id){
        $advert=Advert::find($id);
        if(!$advert)
        {
            return $this->apiResponse(null,'Advert not found',404);
        }
        $advert->delete();
        if($advert)
        {
            return $this->apiResponse(null,'Advert has been deleted',200);
        }
    }
}
