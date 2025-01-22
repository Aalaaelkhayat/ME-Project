<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Models\Address;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $users = UserResource::collection(User::all());
        return $this->apiResponse($users, 'users has been fetched successfully', 200);
    }
    public function showMore($id)
    {
        $user = User::with(['products', 'products.images', 'addresses', 'addresses.mobiles', 'socialaccounts', 'offers', 'experiences', 'certificates', 'rates'])->find($id);
        if ($user) {
            return $this->apiResponse(new UserResource($user), 'Record has been fetched successfully', 200);
        } else {
            return $this->apiResponse(null, 'User not found', 404);
        }
    }

    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|max:255',
    //         'email' => 'required|max:255|unique:users',
    //         'password' => 'required',
    //         'mobile' => 'required',
    //         'spelization_id' => 'required|max:3|integer',
    //         'title' => 'required',
    //         'description' => 'required',
    //         'countryNameCode' => 'required',
    //         'countryPhoneCode' => 'required',
    //         'registerationDate' => 'required|date',
    //         'image' => 'required',
    //         'active' => 'required|max:1|integer',
    //         'hide' => 'required|max:1|integer',
    //         'availableForWork' => 'required|max:1|integer',
    //         'trusted' => 'required|max:1|integer',
    //         'lock' => 'required|max:1|integer',
    //         'font_color' => 'required',
    //         'background_color' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->apiResponse(null, $validator->errors(), 400);
    //     }
    //     $user = User::find($id);
    //     if (!$user) {
    //         return $this->apiResponse(null, 'user not found', 404);
    //     }
    //     $user->update($request->all());
    //     if ($user) {
    //         return $this->apiResponse(new userResource($user), 'user has been updated', 201);
    //     }
    // }
    public function updateFcmToken(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $user = User::find($id);
        if (!$user) {
            return $this->apiResponse(null, 'user not found', 404);
        }
        $user->update($request->all());
        if ($user) {
            return $this->apiResponse(new userResource($user), 'fcm of user has been updated', 201);
        }
    }
    // Search with token:
    public function getUserByToken($token)
    {
        // dd($token);
        // $user=User::find($token);
        $user = User::withCount('schedules')->with(['products', 'products.images', 'addresses', 'addresses.mobiles', 'socialaccounts', 'offers', 'experiences', 'certificates', 'rates'])->where('token', $token)->first();
        // $user = User::where('token', $token)->first();
        // dd($user);
        if ($user) {
            return $this->apiResponse(new UserResource($user), 'Record has been fetched successfully', 200);
        } else {
            return $this->apiResponse(null, 'User not found', 404);
        }
    }

    // public function showLess($id)
    // {
    //     $user = User::with([
    //         'products' => function ($query) {
    //             $query->take(2);
    //         },
    //         'products.images',
    //         'addresses' => function ($query) {
    //             $query->take(2);
    //         },
    //         'addresses.mobiles',
    //         'socialaccounts',
    //         'offers' => function ($query) {
    //             $query->take(2);
    //         },
    //         'experiences' => function ($query) {
    //             $query->take(2);
    //         },
    //         'certificates' => function ($query) {
    //             $query->take(2);
    //         },
    //         'rates' => function ($query) {
    //             $query->take(5);
    //         },
    //     ])->find($id);
    //     if ($user) {
    //         return $this->apiResponse(new userResource($user), 'Record has been fetched successfully', 200);
    //     } else {
    //         return $this->apiResponse(null, 'User not found', 404);
    //     }
    // }
    // Search with token:
    public function getUserByTokenLess($token)
    {
        $user = User::withCount('schedules')  // Count of user's schedules
            ->with([
                'products' => function ($query) {
                    $query->take(2);
                },
                'products.images',
                'addresses' => function ($query) {
                    $query->withCount('schedules')->take(2); // Count of schedules for each address
                },
                'addresses.mobiles',
                'socialaccounts',
                'offers' => function ($query) {
                    $query->take(2);
                },
                'experiences' => function ($query) {
                    $query->take(2);
                },
                'certificates' => function ($query) {
                    $query->take(2);
                },
                'rates' => function ($query) {
                    $query->take(2);
                },
            ])
            ->where('token', $token)
            ->first();

        if ($user) {
            return $this->apiResponse(new UserResource($user), 'Record has been fetched successfully', 200);
        } else {
            return $this->apiResponse(null, 'User not found', 404);
        }
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->apiResponse(null, 'user not found', 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'sometimes|required|max:255|unique:users,email,' . $user->id,
            'password' => 'required',
            'mobile' => 'required',
            'spelization_id' => 'required|max:3|integer',
            'title' => 'required',
            'description' => 'required',
            'countryNameCode' => 'required',
            'countryPhoneCode' => 'required',
            'registerationDate' => 'required|date',
            'active' => 'required|max:1|integer',
            'hide' => 'required|max:1|integer',
            'availableForWork' => 'required|max:1|integer',
            'trusted' => 'required|max:1|integer',
            'lock' => 'required|max:1|integer',
            'font_color' => 'required',
            'background_color' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(),'invalid data', 400);
        }
        // Update only the fields that are present in the request
        $data = $request->all();
        // Check if the password is present, if so, encrypt it
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        else {
            unset($data['password']); // Remove password from data if not provided
        }
        // Update user with validated data
        $user->update($data);
        if ($user) {
            return $this->apiResponse(new userResource($user), 'user has been updated', 201);
        }
    }

    public function uploadImage(Request $request)
    {
        // Validate the request for user ID and image file
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id', // Validate the user ID
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(), 'Bad Request', 400);
        }
        // Find the user by ID
        $user = User::find($request->input('id'));
        if (!$user) {
            return $this->apiResponse(null, 'user not found', 404);
        }
        // Check if an image file was uploaded
        // Check if an image file was uploaded
        if ($request->hasFile('image')) {
            // Generate a unique filename using the user ID and file's original extension
            $extension = $request->image->extension();
            $filename = $user->id . '.' . $extension; // Use user ID as filename
            // Store the image in the specified directory
            $path = $request->file('image')->storeAs('public/images/users', $filename);
            // Save only the filename in the database
            $user->image = $filename; // Save only the filename, without the path
            $user->save(); // Save the updated user record
        }
        // Return a success message
        // return response()->json(['message' => 'Image uploaded successfully.', 'image' => $user->image], 200);
        return $this->apiResponse(['image' => $user->image], 'Image uploaded successfully.', 201);
    }
    // Update the existing image for a user
    public function updateImage(Request $request)
    {
        // Validate the request for user ID and image file
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id', // Validate the user ID
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(), 'Bad Request', 400);
        }
        // Find the user by ID
        $user = User::find($request->input('id'));
        if (!$user) {
            return $this->apiResponse(null, 'user not found', 404);
        }
        // Handle the uploaded image file
        if ($request->hasFile('image')) {
            // Generate a new filename
             // Generate a unique filename using the user ID and file's original extension
            $extension = $request->image->extension();
            $filename = $user->id . '.' . $extension; // Use user ID as filename
            // Store the file in the specified location
            $path = $request->file('image')->storeAs('public/images/users', $filename);
            // Save the image path to the user's record if necessary
            $user->image = $filename; // Save the accessible image path
            $user->save();
        }
        // return response()->json(['message' => 'Image updated successfully.', 'image' => $user->image], 200);
        return $this->apiResponse(['image' => $user->image], 'Image updated successfully.', 200);
    }
}
