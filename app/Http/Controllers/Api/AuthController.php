<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'mobile' => 'required',
    //         'password' => 'required|string|min:6',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }
    //     if (! $token = auth()->attempt($validator->validated())) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }
    //     return $this->createNewToken($token);
    // }

    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'mobile' => 'required|string',
    //         'password' => 'required|string|min:6',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->apiResponse($validator->errors(), 'Validation errors', 422);
    //     }
    //     $credentials = $validator->validated();
    //     // Attempt to log the user in with the provided credentials
    //     if (Auth::attempt($credentials)) {
    //         // Authentication passed...
    //         $user = Auth::user();
    //         return $this->apiResponse($user, 'Login successful', 200);
    //     }
    //     // return response()->json(['error' => 'Unauthorized'], 401);
    //     return $this->apiResponse(null, 'Unauthorized', 401);
    // }


    // public function login(Request $request)
    // {
    //     // Validate the request
    //     $validator = Validator::make($request->all(), [
    //         'mobile' => 'required|string',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->apiResponse($validator->errors(), 'Validation errors', 422);
    //     }

    //     $credentials = $validator->validated();
    //     $user = User::where('mobile', $credentials['mobile'])->first();

    //     // Check if user exists
    //     if (!$user) {
    //         return $this->apiResponse(null, 'Mobile number not found.', 404);
    //     }

    //     // Attempt to log the user in with the provided credentials
    //     if (Auth::attempt(['mobile' => $credentials['mobile'], 'password' => $credentials['password']])) {
    //         // Authentication passed...
    //         $user = Auth::user();
    //         return $this->apiResponse($user, 'Login successful', 200);
    //     }
    //     // If authentication fails, return specific message
    //     return $this->apiResponse(null, 'Invalid password.', 401);
    // }
    public function login(Request $request)
    {
        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(), 'Validation errors', 422);
        }

        // Get validated credentials
        $credentials = $validator->validated();
        $user = User::where('mobile', $credentials['mobile'])->first();

        // Check if user exists
        if (!$user) {
            return $this->apiResponse(null, 'Mobile number not found.', 404);
        }

        // Attempt to authenticate the user
        if (Auth::attempt(['mobile' => $credentials['mobile'], 'password' => $credentials['password']])) {
            // Authentication passed
            $user = Auth::user();
            return $this->apiResponse($user, 'Login successful', 200);
        }

        // If authentication fails, check password explicitly
        if (!Hash::check($credentials['password'], $user->password)) {
            return $this->apiResponse(null, 'Invalid password.', 401);
        }

        // If user exists, but login failed
        return $this->apiResponse(null, 'Authentication failed.', 401);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|between:2,100',
    //         'email' => 'required|string|email|max:100|unique:users',
    //         'password' => 'required|string|confirmed|min:6',
    //         'mobile' => 'required|max:100|unique:users',
    //         'spelization_id' => 'required|integer',
    //         'title' => 'required',
    //         'description' => 'required',
    //         'countryNameCode' => 'required',
    //         'countryPhoneCode' => 'required',
    //         'registerationDate' => 'required|date',
    //         'image' => 'required',
    //         // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'active' => 'required|integer|max:1',
    //         'hide' => 'required|integer|max:1',
    //         'availableForWork' => 'required|integer|max:1',
    //         'lock' => 'required|integer|max:1',
    //         'trusted' => 'required|integer|max:1',
    //         'font_color' => 'required',
    //         'background_color' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->apiResponse($validator->errors(), 'Invalid data', 400);
    //         // return response()->json($validator->errors(),400);
    //     }
    //     $user = User::create(array_merge(
    //         $validator->validated(),
    //         ['password' => bcrypt($request->password)]
    //     ));
    //     return $this->apiResponse($user, 'User successfully registered', 201);
    //     // return response()->json($user);
    // }

    public function register(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'mobile' => 'required|string|max:100|unique:users',
            'spelization_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'countryNameCode' => 'required|string',
            'countryPhoneCode' => 'required|string',
            'registerationDate' => 'required|date',
            // 'image' => 'required|string',
            'active' => 'required|boolean',
            'hide' => 'required|boolean',
            'availableForWork' => 'required|boolean',
            'lock' => 'required|boolean',
            'trusted' => 'required|boolean',
            'font_color' => 'required|string',
            'background_color' => 'required|string',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(), 'Invalid data', 400);
        }

        // Create the user and store in database
        $user = User::create(array_merge(
            $validator->validated(),
            // ['password' => bcrypt($request->password)] // Hashing the password
        ));

        // Return success response
        return $this->apiResponse($user, 'User successfully registered', 201);
    }
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|between:2,100',
    //         'email' => 'required|string|email|max:100|unique:users',
    //         'password' => 'required|string|confirmed|min:6',
    //         'mobile' => 'required|max:100|unique:users',
    //         'spelization_id' => 'required|integer',
    //         'title' => 'required',
    //         'description' => 'required',
    //         'countryNameCode' => 'required',
    //         'countryPhoneCode' => 'required',
    //         'registerationDate' => 'required|date',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'active' => 'required|integer|max:1',
    //         'hide' => 'required|integer|max:1',
    //         'availableForWork' => 'required|integer|max:1',
    //         'lock' => 'required|integer|max:1',
    //         'trusted' => 'required|integer|max:1',
    //         'font_color' => 'required',
    //         'background_color' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->apiResponse(null, $validator->errors(), 400);
    //     }
    //     $imagePath = $request->file('image')->store('images/users', 'public');
    //     $user = User::create(array_merge(
    //         $validator->validated(),
    //         ['password' => bcrypt($request->password),
    //             'image' => $imagePath]
    //     ));
    //     return $this->apiResponse($user, 'User successfully registered', 201);
    // }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            // 'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 525600,
            'user' => auth()->user(),
        ]);
    }
}
