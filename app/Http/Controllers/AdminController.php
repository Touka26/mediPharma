<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Pharmacist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{

    // Log in for Admin
    public function login(Request $request)
    {
        $adminLogin = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($adminLogin->fails()) {

            return $adminLogin->errors()->all();

        }
        $admin = Admin::query()->where('email', '=', $request->email)->first();

        if (!$admin) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $authToken = $admin->createToken('auth-token')->plainTextToken;
        return response()->json([
            'Admin' => $admin,
            'access_token' => $authToken,
            'message' => 'Done'

        ]);
    }

//----------------------------------------------------------------------------------------------------

    //display all pharmacists
    public function getPharmacist()
    {

        $pharmacists = DB::table('pharmacists')
            ->where('active', '=', 0)
            ->select('id', 'first_name', 'middle_name', 'last_name',
                'registration_number', 'registration_date', 'released_on_date',
                'city', 'region', 'name_of_pharmacy', 'landline_phone_number', 'mobile_number',
                'copy_of_the_syndicate_card_url', 'image_url', 'active')
            ->get();

        // Convert active value to boolean
        $pharmacists = $pharmacists->map(function ($pharmacist) {
            $pharmacist->active = $pharmacist->active == 1 ? true : false;
            return $pharmacist;
        });

        return response()->json([
            'pharmacists'=>$pharmacists
        ]);
    }

//----------------------------------------------------------------------------------------------------

    //display pharmacist by id
    public function pharmacistByID($id)
    {
        $pharmacist = Pharmacist::query()->where('id', $id)->first();
        if ($pharmacist == null) {

            return response()->json([
                'message' => 'Pharmacist not found'
            ], 404);

        }
        $pharmacist=Pharmacist::query()->where('id', '=' ,$id)->select('id', 'first_name', 'middle_name', 'last_name',
            'registration_number', 'registration_date', 'released_on_date',
            'city', 'region', 'name_of_pharmacy', 'landline_phone_number', 'mobile_number',
            'copy_of_the_syndicate_card_url', 'image_url', 'active')->get();
        return response()->json(['message' => 'pharmacist by ID', $pharmacist],200);
    }


//----------------------------------------------------------------------------------------------------

    //update FCM token
    public function updateFCMToken(Request $request, $id)
    {
        $pharmacist = Pharmacist::query()->find($id);

        if (!$pharmacist) {
            return response([
                'message' => 'Invalid ID'
            ], 422);
        }

        $request->validate([
            'FCM_token' => 'required'
        ]);

        $pharmacist->update([
            'FCM_token' => $request->FCM_token,
        ]);

        return response()->json([
            'message' => 'FCM token updated successfully'
        ]);
    }


//----------------------------------------------------------------------------------------------------

    //send Accept notification
    public function sendAcceptNoti($id)
    {
        // Get the pharmacist
        $pharmacist = Pharmacist::query()->find($id);

        if (!$pharmacist) {
            return response()->json([
                'message' => 'Pharmacist not found'
            ], 404);
        }

        // Update the 'active' field of the pharmacist
        $pharmacist->update(['active' => 1]);

        // Set the values for admin and pharmacist IDs
        $adminId = 1; // Replace with the actual admin ID
        $pharmacistId = $pharmacist->id;

        // Store notification for the pharmacist
        $notification = $pharmacist->notifications()->create([
            'admin_id' => $adminId,
            'pharmacist_id' => $pharmacistId,
            'title' => 'Authentication Message',
            'body' => 'Your order is accepted, your information is correct. Welcome to MediPharma!',
            'image_url' => '/storage/files/images/logo.png'
        ]);

        // Get FCM token and server key
        $fcmToken = $pharmacist->FCM_token;
        $serverKey = env('FCM_SERVER_KEY');

        // Send FCM notification
        $response = Http::acceptJson()->withToken($serverKey)->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $fcmToken,
            'notification' => [
                'title' => $notification->title,
                'body' => $notification->body,
                'sound' => 'default'
            ],
            'data' => [
                'image' => $notification->image_url,
            ]
        ]);

        return json_decode($response);
    }

//----------------------------------------------------------------------------------------------------

    //send reject notification
    public function sendRejectNoti($id)
    {
        // Get the pharmacist
        $pharmacist = Pharmacist::query()->find($id);

        if (!$pharmacist) {
            return response()->json([
                'message' => 'Pharmacist not found'
            ], 404);
        }

        // Get FCM token and server key
        $fcmToken = $pharmacist->FCM_token;
        $serverKey = env('FCM_SERVER_KEY');

        // Send FCM notification
        $response = Http::acceptJson()->withToken($serverKey)->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $fcmToken,
            'notification' => [
                'title' => 'Authentication Message',
                'body' => 'Your order is rejected, because your information isn\'t correct.
                           Please verify your information.',
                'image' => 'http://127.0.0.1:8000/storage/files/images/logo.png',
                'sound' => 'default'
            ]
        ]);

        // Delete the pharmacist
        $pharmacist->delete();

        return json_decode($response);
    }

//----------------------------------------------------------------------------------------------------


}

