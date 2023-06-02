<?php

namespace App\Http\Controllers;

use App\Models\Pharmacist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PharmacistController extends Controller
{

    //show a specific profile

    public function index($id)
    {
        $pharmacist = Pharmacist::query()->find($id)->get();
        return response()->json(['message' => 'this is my profile', $pharmacist], 200);
    }

    //------------------------------------------------------------------------

    //Register for Pharmacist

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:pharmacists,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:30'],
            'middle_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'registration_number' => ['required', 'numeric', 'min:5'],
            'registration_date' => ['required'],
            'released_on_date' => ['required'],
            'city' => ['required', 'string', 'max:30'],
            'region' => ['required', 'string', 'max:30'],
            'name_of_pharmacy' => ['required', 'string', 'max:30'],
            'landline_phone_number' => ['required', 'numeric', 'min:15'],
            'mobile_number' => ['required', 'numeric', 'min:15'],
            'copy_of_the_syndicate_card_url' => 'required|file',//'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'required|email',
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'image_url' => 'required|file',//'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'financial_fund' => ['required'],
        ]);

        if ($request->hasFile('copy_of_the_syndicate_card_url')) {
            $destination_path = 'public/files/syndicateCard';
            $copy_of_the_syndicate_card_url = $request->file('copy_of_the_syndicate_card_url');
            $file_name = $copy_of_the_syndicate_card_url->getClientOriginalName();
            $path = $request->file('copy_of_the_syndicate_card_url')->storeAs($destination_path, $file_name);
            $url1 = Storage::url($path);
        }

        if ($request->hasFile('image_url')) {
            $destination_path = 'public/files/images';
            $image_url = $request->file('image_url');
            $file_name = $image_url->getClientOriginalName();
            $path = $request->file('image_url')->storeAs($destination_path, $file_name);
            $url = Storage::url($path);
        }

        $pharmacist = Pharmacist::query()->create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'registration_number' => $request->registration_number,
            'registration_date' => $request->registration_date,
            'released_on_date' => $request->released_on_date,
            'city' => $request->city,
            'region' => $request->region,
            'name_of_pharmacy' => $request->name_of_pharmacy,
            'landline_phone_number' => $request->landline_phone_number,
            'mobile_number' => $request->mobile_number,
            'copy_of_the_syndicate_card_url' => $url1,
            'email' => $request->email,
            'password' => bcrypt(request('password')),
            'password_confirmation' => bcrypt(request('password_confirmation')),
            'image_url' => $url,
            'financial_fund' => $request->financial_fund,
        ]);

        $authToken = $pharmacist->createToken('auth-token')->plainTextToken;
        return response()->json([
            'The Pharmacist' => $pharmacist,
            'Token' => $authToken,
            'message' => 'Done'
        ]);
    }

    //------------------------------------------------------------------------

    //Login for Pharmacist

    public function login(Request $request)
    {
        $pharmacistLogin = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($pharmacistLogin->fails()) {

            return $pharmacistLogin->errors()->all();

        }
        $pharmacistLogin = Pharmacist::query()->where('email', '=', $request->email)->first();

        if (!$pharmacistLogin || !Hash::check($request->password, $pharmacistLogin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
                'password' => ['The provided credentials are incorrect.']
            ]);
        }

        $authToken = $pharmacistLogin->createToken('auth-token')->plainTextToken;
        return response()->json([
            'Pharmacist' => $pharmacistLogin,
            'access_token' => $authToken,
            'message' => 'Done'

        ]);
    }

    //log out by delete token

    //----------------------------------------------------------------------

    public function logout(Request $request)
    {
        $deleted = $request->user()->currentAccessToken()->delete();
        return $deleted == '1' ? response()->json(['message' => 'Deleted']) : $deleted;

    }

    //----------------------------------------------------------------------

    //delete account by ID

    public function deleteAccount($id)
    {
        if ($pharmacist = Pharmacist::query()->find($id)) {
            $pharmacist->delete();
            return response()->json(['message: ' => 'deleted'], 200);
        } else {
            return response()->json(['message: ' => 'invalid ID'], 422);
        }
    }

    //----------------------------------------------------------------------

    //update pharmacist information

    public function update(Request $request, int $id)
    {
        $pharmacist = Pharmacist::query()->find($id);
        if ($pharmacist == null) {
            return response([
                'message' => 'Invalid ID'
            ], 422);
        }

        $first_name = $request->input('first_name');
        $middle_name = $request->input('middle_name');
        $last_name = $request->input('last_name');
        $city = $request->input('city');
        $region = $request->input('region');
        $name_of_pharmacy = $request->input('name_of_pharmacy');
        $landline_phone_number = $request->input('landline_phone_number');
        $mobile_number = $request->input('mobile_number');
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));
        $password_confirmation = bcrypt($request->input('password_confirmation'));
        $financial_fund = $request->input('financial_fund');

        if ($request->hasFile('copy_of_the_syndicate_card_url')) {
            $destination_path = 'public/files/syndicateCard';
            $copy_of_the_syndicate_card_url = $request->file('copy_of_the_syndicate_card_url');
            $file_name = $copy_of_the_syndicate_card_url->getClientOriginalName();
            $path = $request->file('copy_of_the_syndicate_card_url')->storeAs($destination_path, $file_name);
            $url1 = Storage::url($path);
            $pharmacist->copy_of_the_syndicate_card_url = $url1;
        }

        if ($request->hasFile('image_url')) {
            $destination_path = 'public/files/images';
            $image_url = $request->file('image_url');
            $file_name = $image_url->getClientOriginalName();
            $path = $request->file('image_url')->storeAs($destination_path, $file_name);
            $url = Storage::url($path);
            $pharmacist->image_url = $url;
        }

        if ($first_name) {
            $pharmacist->first_name = $first_name;
        }
        if ($middle_name) {
            $pharmacist->middle_name = $middle_name;
        }
        if ($last_name) {
            $pharmacist->last_name = $last_name;
        }

        if ($city) {
            $pharmacist->city = $city;
        }
        if ($region) {
            $pharmacist->region = $region;
        }
        if ($name_of_pharmacy) {
            $pharmacist->name_of_pharmacy = $name_of_pharmacy;
        }
        if ($landline_phone_number) {
            $pharmacist->landline_phone_number = $landline_phone_number;
        }

        if ($mobile_number) {
            $pharmacist->mobile_number = $mobile_number;
        }

        if ($email) {
            $pharmacist->email = $email;
        }

        if ($password) {
            $pharmacist->password = $password;
        }
        if ($password_confirmation) {
            $pharmacist->password_confirmation = $password_confirmation;
        }

        if ($financial_fund) {
            $pharmacist->financial_fund = $financial_fund;
        }

        $pharmacist->save();
        return response()->json([
            'message' => 'Updated Successfully', $pharmacist
        ]);

    }


}
