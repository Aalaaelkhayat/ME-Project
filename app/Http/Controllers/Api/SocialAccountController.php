<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\SocialaccountResouce;
use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SocialAccountController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'user_id'=>'required',
            'type'=>'required',
            'account'=>'required',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $social_account=SocialAccount::create($request->all());
        if($social_account){
            return $this->apiResponse(new SocialaccountResouce($social_account),'Social Account has been created successfully',201);
        }
        else{
            return $this->apiResponse(null,'Bad Request',400);
        }
    }
    public function update(Request $request,$id)
    {
        $social_account=SocialAccount::find($id);
        if(!$social_account)
        {
            return $this->apiResponse(null,'Social Account not found',404);
        }
        $validator=Validator::make($request->all(),[
            'user_id'=>'required',
            'type'=>'required',
            'account'=>'required',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $social_account->update($request->all());
        if($social_account)
        {
            return $this->apiResponse(new SocialaccountResouce($social_account),'Social Account has been updated',201);
        }
    }
    public function destroy($id)
    {
        $social_account=SocialAccount::find($id);
        if(!$social_account)
        {
            return $this->apiResponse(null,'Social Account Not Found',404);
        }
        $social_account->delete();
        if($social_account)
        {
            return $this->apiResponse(null,'Social Account has been deleted',200);
        }
    }
}
