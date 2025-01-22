<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'address' => 'required',
            'note' => 'required',
            'lat' => 'required',
            'lang' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $address = Address::create($request->all());
        if ($address) {
            return $this->apiResponse(new AddressResource($address), 'Address has been created successfully', 201);
        } else {
            return $this->apiResponse(null, 'Bad Request', 400);
        }
    }
    public function update(Request $request, $id)
    {
        $address = Address::find($id);
        if (!$address) {
            return $this->apiResponse(null, 'Address not found', 404);
        }
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'address' => 'required',
            'note' => 'required',
            'lat' => 'required',
            'lang' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $address->update($request->all());
        if ($address) {
            return $this->apiResponse(new AddressResource($address), 'Address has been updated', 201);
        }
    }
    public function destroy($id)
    {
        $address = Address::find($id);
        if (!$address) {
            return $this->apiResponse(null, 'Address Not Found', 404);
        }
        $address->delete();
        if ($address) {
            return $this->apiResponse(null, 'Address has been deleted', 200);
        }
    }
    public function getUserAddresses($user_id)
    {
        $user_addresses = AddressResource::collection(Address::with(['mobiles'])
        ->withCount('schedules') // Add count of related schedules
        ->where('user_id', $user_id)->get());
        // dd($user_addresses);
        if ($user_addresses) {
            return $this->apiResponse($user_addresses, 'User addresses records has been fetched successfully', 200);
        } else {
            return $this->apiResponse(null, 'User not found', 404);
        }
    }
    public function nearby(Request $request)
    {
        // Validate required fields and optional fields
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric',
            'availability' => 'required|in:0,1',
            'name' => 'sometimes|string|nullable',
            'spelization' => 'sometimes|numeric',
            'mobile' => 'sometimes|string|nullable',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        // Extract parameters
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 5); // Default radius in kilometers
        // Build the query
        $query = Address::with('user');
        // Apply the filtering for visible users
        $query->whereHas('user', function ($query) {
            $query->visible(); // Filter only visible users
        });
        // Add filter for geographical location
        if ($latitude && $longitude) {
            $query->whereRaw("ST_Distance_Sphere(point(lang, lat), point(?, ?)) <= ?", [
                $longitude,
                $latitude,
                $radius * 1000
            ]);
        }
        // Filter by availability, now required
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('availableForWork', $request->input('availability'));
        });
        // Filter by the name of the user
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->input('name')}%");
            });
        }
        // Filter by the mobile number of the user
        if ($request->filled('mobile')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('mobile', 'LIKE', "%{$request->input('mobile')}%");
            });
        }
        // Filter by the spelization of the user
        if ($request->filled('spelization')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('spelization_id', $request->input('spelization'));
            });
        }
        // Execute the query and get the results
        $addresses = $query->get();
        if ($addresses->isEmpty()) {
            return $this->apiResponse(null, 'No addresses found matching the provided filter.', 404);
        }
        // Return results as JSON
        if ($addresses) {
            return $this->apiResponse($addresses, 'Addresses has been fetched successfully', 200);
        } else {
            return $this->apiResponse(null, 'No addresses found matching the provided filter.', 404);
        }
    }
}
