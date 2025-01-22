<?php
//  an appointment booking system
namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    use ApiResponseTrait;
    public function bookings()
    {
        $bookings = BookingResource::collection(Booking::all());
        return $this->apiResponse($bookings, 'Bookings has been fetched successfully', 200);
    }
    public function book(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'profile_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id',
            'address_id' => 'required|exists:addresses,id',
            'schedule_id' => 'required|exists:schedules,id',
            'period_id' => 'required|exists:periods,id',
            'appointment_id' => 'required|exists:appointments,id',
            'confirm_status' => 'required|integer|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 422);
        }
        // Check if the appointment is available
        $appointment = Appointment::find($request->appointment_id);
        if ($appointment->status != 0) {
            return $this->apiResponse(null, 'Appointment is not available for booking.', 400);
        }
        // Create a new booking
        $booking = Booking::create([
            'profile_id' => $request->profile_id,
            'user_id' => $request->user_id,
            'address_id' => $request->address_id,
            'schedule_id' => $request->schedule_id,
            'period_id' => $request->period_id,
            'appointment_id' => $request->appointment_id,
            'confirm_status' => 0, // Initially not confirmed
        ]);
        // Update schedule status to done
        $appointment->status = 1; // Mark as done
        $appointment->save();
        return $this->apiResponse(new BookingResource($booking), 'Booking of appointment has been created successfully', 201);
    }
    public function destroy($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return $this->apiResponse(null, 'Booking not found', 404);
        }
        // Get the related appointment
        $appointment = Appointment::find($booking->appointment_id);
        if (!$appointment) {
            return $this->apiResponse(null, 'Related appointment not found.', 404);
        }
        $booking->delete();
        // Update the appointment status back to 0 (available)
        $appointment->status = 0; // Mark as available
        $appointment->save();
        if ($booking) {
            return $this->apiResponse(null,'Booking has been deleted and appointment status updated successfully.', 200);
        }
    }
}
