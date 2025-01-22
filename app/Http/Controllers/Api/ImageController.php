<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'image'      => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(),'invalid data', 422);
        }
        // Handle file upload
        $imagePath = $request->file('image')->store('images/products', 'public');
        //just the image name from the path
        $imageName = basename($imagePath);
        $image = Image::create([
            'user_id'    => $request->user_id,
            'product_id' => $request->product_id,
            'image_path' => $imageName,
        ]);
        return $this->apiResponse($image,'Image uploaded successfully', 201);
    }
    public function destroy($id)
    {
        // Find the image record by ID
        $image = Image::find($id);
        if (!$image) {
            return $this->apiResponse(null,'Image not found', 404);
        }
        // Get the full image path for deletion
        $imagePath = 'images/products/' . $image->image_path;
        // Delete the image from storage
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
        $image->delete();
        return $this->apiResponse(null,'Image deleted successfully', 200);
    }
    public function getUserImages($user_id)
    {
        $user_images=ImageResource::collection(Image::where('user_id', $user_id)->get());
        // dd($user_images);
        if($user_images)
        {
            return $this->apiResponse($user_images,'User images records has been fetched successfully',200);
        }
        else
        {
            return $this->apiResponse(null,'User not found',404);
        }
    }
}
