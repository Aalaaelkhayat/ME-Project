<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\RateResource;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    use ApiResponseTrait;
    public function getSchedules($address_id)
    {
        $schedules = ScheduleResource::collection(
            Schedule::where('address_id', $address_id)
                // ->where('status', 0)
                ->get()
        );
        if ($schedules) {
            return $this->apiResponse($schedules, 'Schedule records of address has been fetched successfully', 200);
        } else {
            return $this->apiResponse(null, 'Schedule not found', 404);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'address_id' => 'required',
            'day' => 'required',
            'date' => 'required',
            // 'time' => 'required',
            // 'status' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $schedule = Schedule::create($request->all());
        if ($schedule) {
            return $this->apiResponse(new ScheduleResource($schedule), 'Schedule has been created', 201);
        } else {
            return $this->apiResponse(null, 'Bad request', 400);
        }
    }
    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return $this->apiResponse(null, 'Schedule not found', 404);
        }
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required',
            // 'address_id' => 'required',
            'day' => 'required',
            'date' => 'required',
            // 'time' => 'required',
            // 'status' => 'required|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $schedule->update($request->all());
        if ($schedule) {
            return $this->apiResponse(new ScheduleResource($schedule), 'Schedule has updated succeessfully', 200);
        } else {
            return $this->apiResponse(null, 'Bad request', 400);
        }
    }
    public function destroy($id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return $this->apiResponse(null, 'Schedule not found', 404);
        }
        $schedule->delete();
        if ($schedule) {
            return $this->apiResponse(null, 'Schedule has been deleted successfully', 200);
        }
    }
    public function getSchedulesAfterDate(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id', // Ensure user_id is provided and valid
            'date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        // Retrieve user_id and requested date from the request
        $userId = $request->input('user_id');
        $requestedDate = $request->input('date');

        // Query schedules where the date is greater than the requested date and matches the user_id
        $schedules = ScheduleResource::collection(Schedule::where('user_id', $userId)
            ->where('date', '>', $requestedDate)
            ->get());

        // Return the fetched schedules
        if ($schedules) {
            return $this->apiResponse($schedules, 'Schedule that greater than requested data succeessfully', 200);
        } else {
            return $this->apiResponse(null, 'Bad request', 400);
        }
    }
    public function getSchedulesFullDetailsByUserId($user_id)
    {
        // Fetch schedules that belong to the given user_id
        $user_schedules = Schedule::where('user_id', $user_id)->get();
        // Check if the schedule collection is empty
        if ($user_schedules->isEmpty())
        {
            return $this->apiResponse(null, 'There are no schedules for this user', 404);
        }
        $user_schedules = ScheduleResource::collection(Schedule::with(['appointments.period', 'address'])
            ->where('user_id', $user_id)
            ->get());
        // Return the fetched schedules
        if ($user_schedules)
        {
            return $this->apiResponse($user_schedules, 'Schedules retrieved successfully', 200);
        }
        else
        {
            return $this->apiResponse(null, 'Bad request', 400);
        }
    }
    public function getSchedulesByAddressId($address_id)
    {
        // Fetch schedules that belong to the given address_id
        $address_schedules = Schedule::where('address_id', $address_id)->get();
        // Check if the schedule collection is empty
        if ($address_schedules->isEmpty())
        {
            return $this->apiResponse(null, 'There are no schedules for this address', 404);
        }
        // $address_schedule = ScheduleResource::collection(Schedule::with(['address'])
        $address_schedules = ScheduleResource::collection(Schedule::where('address_id', $address_id)->get());
        // Return the fetched schedules
        if ($address_schedules)
        {
            return $this->apiResponse($address_schedules, 'Schedules retrieved successfully', 200);
        }
        else
        {
            return $this->apiResponse(null, 'Bad request', 400);
        }
    }

}
