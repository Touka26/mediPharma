<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Pharmacist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'pharmacists',
            $pharmacists
        ]);
    }
}

