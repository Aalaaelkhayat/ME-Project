<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\PeriodResource;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    use ApiResponseTrait;
    public function getPeriodsByAddressId($address_id)
    {
        $schedule_periods = Period::whereHas('schedule', function ($query) use ($address_id) {
                $query->where('address_id', $address_id);
            })
            ->whereHas('appointments', function ($query) {
                $query->where('status', 0);
            })
            ->with('appointments') // Eager load appointments
            ->get();
        // Check if the schedule collection is empty
        if ($schedule_periods->isEmpty()) {
            return $this->apiResponse(null, 'There are no schedules for this address', 404);
        }
        if ($schedule_periods) {
            return $this->apiResponse(PeriodResource::collection($schedule_periods), 'Periods with related schedule has been retrieved successfully', 200);
        } else {
            return $this->apiResponse(null, 'Bad request', 400);
        }
    }
    public function getPeriodsByScheduleId($schedule_id)
    {
        // Fetch periods by schedule_id
        $periods = Period::where('schedule_id', $schedule_id)
            ->with('appointments') // Eager load appointments
            ->get();

        // Check if the periods collection is empty
        if ($periods->isEmpty()) {
            return $this->apiResponse(null, 'There are no periods for this schedule', 404);
        }
        if ($periods) {
            return $this->apiResponse(PeriodResource::collection($periods),'Periods with related appointments have been retrieved successfully', 200);
        } else {
            return $this->apiResponse(null, 'Bad request', 400);
        }
    }
}
