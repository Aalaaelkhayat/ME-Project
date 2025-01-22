<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RateController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'user_id'=>'required|integer',
            'profile_id'=>'required|integer',
            'rating'=>'required|integer',
            'comment'=>'required',
            'date'=>'required|date',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $rate=Rate::create($request->all());
        if($rate)
        {
            return $this->apiResponse(new RateResource($rate),'Rate has been created successfully',201);
        }
        else
        {
            return $this->apiResponse(null,'Bad request',400);
        }
    }
    public function update(Request $request,$id)
    {
        $rate=Rate::find($id);
        if(!$rate)
        {
            return $this->apiResponse(null,'Rate not found',404);
        }
        $validator=Validator::make($request->all(),[
            // 'user_id'=>'required|integer',
            // 'profile_id'=>'required|integer',
            'rating'=>'required|integer',
            'comment'=>'required',
            // 'date'=>'required|date',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $rate->update($request->all());
        if($rate)
        {
            return $this->apiResponse(new RateResource($rate),'Rate details has been updated',201);
        }
    }
    public function destroy($id)
    {
        $rate=Rate::find($id);
        if(!$rate)
        {
            return $this->apiResponse(null,'Rate not found',404);
        }
        $rate->delete();
        if($rate)
        {
            return $this->apiResponse(null,'Rate has been deleted',200);
        }
    }
    public function getUserRates($user_id)
    {
        $user_rates=RateResource::collection(Rate::where('user_id',$user_id)->get());
        // dd($user_rates);
        if($user_rates)
        {
            return $this->apiResponse($user_rates,'User offers records has been fetched successfully',200);
        }
        else
        {
            return $this->apiResponse(null,'User not found');
        }
    }
}
