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
                'copy_of_the_syndicate_card_url', 'image_url', 'active','FCM_token')
            ->get();

        // Convert active value to boolean
        $pharmacists = $pharmacists->map(function ($pharmacist) {
            $pharmacist->active = $pharmacist->active == 1 ? true : false;
            return $pharmacist;
        });

        return response()->json([
            'pharmacists' => $pharmacists
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
        $pharmacist = Pharmacist::query()->where('id', '=', $id)->select('id', 'first_name', 'middle_name', 'last_name',
            'registration_number', 'registration_date', 'released_on_date',
            'city', 'region', 'name_of_pharmacy', 'landline_phone_number', 'mobile_number',
            'copy_of_the_syndicate_card_url', 'image_url', 'active','FCM_token')->get();
        return response()->json(['message' => 'pharmacist by ID', $pharmacist], 200);
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

    public function sendAcceptNoti(Request $request)
    {
        $pharmacist = Pharmacist::where('FCM_token', $request->FCM_token)->first();

        if (!$pharmacist) {
            return response()->json([
                'message' => 'Pharmacist not found'
            ], 404);
        }

        // Update the 'active' field of the pharmacist
        $pharmacist->update(['active' => true]);
        $adminId = 1;
        $pharmacistId = $pharmacist->id;
        // Store notification in the notifications table
        $notification = $pharmacist->notifications()->create([
            'admin_id' => $adminId,
            'pharmacist_id' => $pharmacistId,
            'title' => 'Authentication Message',
            'body' => 'Your order is accepted, your information is correct. Welcome to MediPharma!',
        ]);

        // Send the notification
        $response = $this->sendNotification($request->FCM_token, [
            "title" => $notification->title,
            "body" => $notification->body
        ]);

        return $response;
    }

//----------------------------------------------------------------------------------------------------

    public function sendRejectNoti(Request $request)
    {
        $pharmacist = Pharmacist::query()->where('FCM_token', $request->FCM_token)->first();

        if (!$pharmacist) {
            return response()->json([
                'message' => 'Pharmacist not found'
            ], 404);
        }
        $response = $this->sendNotification($request->FCM_token, [
            "title" => 'Authentication Message',
            "body" => 'Your order is refused!!, your information is not correct'
        ]);
        $pharmacist = Pharmacist::query()->where('FCM_token', $request->FCM_token)->first();
        $pharmacist->delete();
        return $response;
    }

//----------------------------------------------------------------------------------------------------

    /**
     * Write code on Method
     *
     * @return bool|string()
     */
    public function sendNotification($FCM_token, $message)
    {
        $SERVER_API_KEY = 'AAAAr00IOb0:APA91bGmphRpTcZ3T2dVhE30dhde5r-Npsr0tvt_kbvrIBwpC7WeWuxbfe_OakmHGf2aPAs7nmOU9lUfG99iB1IF1xRRdg7chKxPbpRIrUifGLz2s4QoYonlHgIH24mLcm4Wv0JDHg9f';

        // payload data, it will vary according to requirement
        $data = [
            "to" => $FCM_token, // for single device id
            "data" => $message
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

//----------------------------------------------------------------------------------------------------


}

