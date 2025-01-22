<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AdvertController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\MobileController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\PeriodController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SocialAccountController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



//Authentication
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'createNewToken']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

//Users
Route::get('/users', [UserController::class, 'index']);
Route::get('/users_show_more/{id}', [UserController::class, 'showMore']);
Route::get('/users_show_less/{id}', [UserController::class, 'showLess']);
//(with token)
Route::get('/users_token/{token}', [UserController::class, 'getUserByToken']);
Route::get('/users_token_show_less/{token}', [UserController::class, 'getUserByTokenLess']);
Route::get('/users/nearby', [UserController::class, 'findNearbyUsers']);
// Route::put('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::post('/users_fcm_token/{id}', [UserController::class, 'updateFcmToken']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::get('/user_images/{user_id}', [ImageController::class, 'getUserImages']); //fetch by user id
Route::get('/user/products/{user_id}', [ProductController::class, 'getUserProducts']); //fetch by user id

//Products
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

//Product_images
Route::post('/images', [ImageController::class, 'store']);
Route::delete('/images/{id}', [ImageController::class, 'destroy']);

//Addresses
Route::get('/user_addresses/{user_id}', [AddressController::class, 'getUserAddresses']); //fetch by user id with schedules_count
Route::put('/addresses', [AddressController::class, 'store']);
Route::post('/addresses/{id}', [AddressController::class, 'update']);
Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
Route::get('/addresses/nearby', [AddressController::class, 'nearby']); //nearby address

//Mobiles
Route::put('/mobiles', [MobileController::class, 'store']);
Route::post('/mobiles/{id}', [MobileController::class, 'update']);
Route::delete('/mobiles/{id}', [MobileController::class, 'destroy']);

//Socialaccounts
Route::put('/socialaccounts', [SocialAccountController::class, 'store']);
Route::post('/socialaccounts/{id}', [SocialAccountController::class, 'update']);
Route::delete('/socialaccounts/{id}', [SocialAccountController::class, 'destroy']);

//Adverts
Route::put('/adverts', [AdvertController::class, 'store']);
Route::post('/adverts/{id}', [AdvertController::class, 'update']);
Route::delete('/adverts/{id}', [AdvertController::class, 'destroy']);

//Certificates
Route::get('/user_certificates/{user_id}', [CertificateController::class, 'getUserCertificates']); //fetch by user id
Route::put('/certificates', [CertificateController::class, 'store']);
Route::post('certificates/{id}', [CertificateController::class, 'update']);
Route::delete('certificates/{id}', [CertificateController::class, 'destroy']);

//Experiences
Route::get('/user_experiences/{user_id}', [ExperienceController::class, 'getUserExperiences']); //fetch by user id
Route::put('/experiences', [ExperienceController::class, 'store']);
Route::post('experiences/{id}', [ExperienceController::class, 'update']);
Route::delete('experiences/{id}', [ExperienceController::class, 'destroy']);

//Offers
Route::get('/user_offers/{user_id}', [OfferController::class, 'getUserOffers']); //fetch by user id
Route::put('/offers', [OfferController::class, 'store']);
Route::post('offers/{id}', [OfferController::class, 'update']);
Route::delete('offers/{id}', [OfferController::class, 'destroy']);

//Rates
Route::get('/user_rates/{user_id}', [RateController::class, 'getUserRates']); //fetch by user id
Route::put('/rates', [RateController::class, 'store']);
Route::post('/rates/{id}', [RateController::class, 'update']);
Route::delete('/rates/{id}', [RateController::class, 'destroy']);

//Schedule
Route::get('/schedules/after-date', [ScheduleController::class, 'getSchedulesAfterDate']);
Route::get('/schedules_full/{user_id}', [ScheduleController::class, 'getSchedulesFullDetailsByUserId']);  // Fetch schedules that belong to the given user_id full object
Route::get('/schedules/{address_id}', [ScheduleController::class, 'getSchedulesByAddressId']);  // Fetch schedules that belong to the given address_id

//Periods
Route::get('/periods/{address_id}', [PeriodController::class, 'getPeriodsByAddressId']);  // Fetch schedules that belong to the given user_id
Route::get('/periods/schedule/{schedule_id}', [PeriodController::class, 'getPeriodsByScheduleId']);  // Fetch periods that belong to the given schedule_id

//Booking
Route::get('/bookings', [BookingController::class, 'bookings']);
Route::post('/bookings', [BookingController::class, 'book']);
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

//User_images
Route::post('/user/upload-image', [UserController::class, 'uploadImage']); //Upload_image_of_user
Route::post('/user/update-image', [UserController::class, 'updateImage']); //Update_image_of_user

Route::post('/change-password', [PasswordController::class, 'changePassword']);
