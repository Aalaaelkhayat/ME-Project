<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    use ApiResponseTrait;

    public function changePassword(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(), 'Invalid Data', 400);
        }
        // Find the user by ID
        $user = User::find($request->id);
        // Check if the current password is correct
        // if (! Hash::check($request->current_password, $user->password)) {
        //     return $this->apiResponse(null, 'Current password is incorrect.', 403);
        // }
        // Update the password with the new password
        // $user->password = Hash::make($request->new_password);
        $user->password =$request->new_password;
        $user->save();
        return $this->apiResponse(null, 'Password changed successfully.', 200);
    }
}
