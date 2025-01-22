<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'user_id'=>'required',
            'title'=>'required',
            'description'=>'required',
            'image'=>'required',
            'active'=>'required',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $offer=Offer::create($request->all());
        if($offer){
            return $this->apiResponse(new OfferResource($offer),'Offer has been created successfully',201);
        }
        else{
            return $this->apiResponse(null,'Bad Request',400);
        }
    }
    public function update(Request $request,$id)
    {
        $offer=Offer::find($id);
        if(!$offer)
        {
            return $this->apiResponse(null,'Offer not found',404);
        }
        $validator=Validator::make($request->all(),[
            // 'user_id'=>'required',
            'title'=>'required',
            'description'=>'required',
            'image'=>'required',
            'active'=>'required',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $offer->update($request->all());
        if($offer)
        {
            return $this->apiResponse(new OfferResource($offer),'Offer has been updated',201);
        }
    }
    public function destroy($id)
    {
        $offer=Offer::find($id);
        if(!$offer)
        {
            return $this->apiResponse(null,'Offer not found',404);
        }
        $offer->delete();
        if($offer)
        {
            return $this->apiResponse(null,'Offer has been deleted',200);
        }
    }
    public function getUserOffers($user_id)
    {
        $user_offers=OfferResource::collection(Offer::where('user_id', $user_id)->get());
        // dd($user_offers);
        if($user_offers)
        {
            return $this->apiResponse($user_offers,'User offers records has been fetched successfully',200);
        }
        else
        {
            return $this->apiResponse(null,'User not found',404);
        }
    }
}
