<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller
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
        $experience=Experience::create($request->all());
        if($experience)
        {
            return $this->apiResponse(new ExperienceResource($experience),'Experience has been created',201);
        }
        else
        {
            return $this->apiResponse(null,'Bad request',400);
        }
    }
    public function update(Request $request,$id)
    {
        $experience=Experience::find($id);
        if(!$experience)
        {
            return $this->apiResponse(null,'Experience not found',404);
        }
        $validator=Validator::make($request->all(),[
            'title'=>'required',
            'body'=>'required',
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null,$validator->errors(),400);
        }
        $experience->update($request->all());
        if($experience)
        {
            return $this->apiResponse(new ExperienceResource($experience),'Experience has updated succeessfully',200);
        }
        else
        {
            return $this->apiResponse(null,'Bad request',400);
        }
    }
    public function destroy($id)
    {
        $experience=Experience::find($id);
        if(!$experience)
        {
            return $this->apiResponse(null,'Experience not found',404);
        }
        $experience->delete();
        if($experience)
        {
            return $this->apiResponse(null,'Experience has been deleted successfully',200);
        }
    }
    public function getUserExperiences($user_id)
    {
        $user_experiences=ExperienceResource::collection(Experience::where('user_id', $user_id)->get());
        // dd($user_experiences);
        if($user_experiences)
        {
            return $this->apiResponse($user_experiences,'User experiences records has been fetched successfully',200);
        }
        else
        {
            return $this->apiResponse(null,'User not found',404);
        }
    }
}
