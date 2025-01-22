<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ApiResponseTrait;

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'required|exists:users,id',
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'active' => 'required|integer|max:1',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->apiResponse($validator->errors(), 'Invalid Data', 400);
    //     }
    //     // Create the product
    //     $product = Product::create($request->only(['user_id', 'title', 'description', 'date', 'active']));
    //     // Handle image uploads
    //     if ($request->hasFile('images')) {
    //         foreach ($request->file('images') as $imageFile) {
    //             // Store the image and get the path
    //             $path = $imageFile->store('images/products', 'public');
    //             // Extract only the image name with its extension
    //             $imageName = basename($path);
    //             // Create an Image record
    //             Image::create([
    //                 'user_id' => $product->user_id,
    //                 'image_path' => $imageName,
    //                 'product_id' => $product->id,
    //             ]);
    //         }
    //     }
    //     // Return the created product with its images
    //     return $this->apiResponse(new ProductResource($product->load('images')), 'Product has been created', 201);
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'active' => 'required|integer|max:1|min:0',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(), 'Invalid Data', 400);
        }
        //new product instance
        $product = new Product();
        $product->user_id = $request->input('user_id');
        $product->title = $request->input('title');
        $product->description = $request->input('description');
        $product->active = $request->input('active');
        $product->date = now();
        $product->save();
        return $this->apiResponse(new ProductResource($product->load('images')), 'Product created successfully.', 201);
    }
    public function update(Request $request, $id)
    {
        // Find the product by id
        $product = Product::find($id);
        if (!$product) {
            return $this->apiResponse(null, 'product not found', 404);
        }
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'active' => 'sometimes|integer|max:1',
            // 'images' => 'array',
            // 'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each image
        ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(), 'Invalid Data', 400);
        }

        // Update the product attributes if present
        $product->update($request->only(['user_id', 'title', 'description', 'date', 'active']));
        // Handle image uploads if provided
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                // Store the image and get the full path
                $path = $imageFile->store('images/products', 'public');
                // Extract only the image name with its extension
                $imageName = basename($path);
                // Create an Image record for each uploaded image
                Image::create([
                    'user_id' => $product->user_id,
                    'image_path' => $imageName, // Save only the image name with extension
                    'product_id' => $product->id,
                ]);
            }
        }

        return $this->apiResponse(new ProductResource($product->load('images')), 'Product has been updated', 200);
    }

    public function destroy($id)
    {
        // Find the product by id with its images
        $product = Product::with('images')->findOrFail($id);
        // Iterate over each associated image
        foreach ($product->images as $image) {
            // Delete the image file from storage
            Storage::disk('public')->delete('images/products/'.$image->image_path);
            // Delete the image record from the database
            $image->delete();
        }
        // Delete the product record from the database
        $product->delete();
        // Return a response indicating success
        return $this->apiResponse(null, 'Product and its images deleted successfully.', 200);
    }

    public function getUserProducts($user_id)
    {
        $user_products = ProductResource::collection(Product::with(['images'])->where('user_id', $user_id)->get());
        if ($user_products) {
            return $this->apiResponse($user_products, 'User products records has been fetched successfully', 200);
        } else {
            return $this->apiResponse(null, 'User not found', 404);
        }
    }
}
