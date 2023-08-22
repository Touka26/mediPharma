<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Pharmacist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Console\Input\Input;

class PharmacistController extends Controller
{

    //get financial fund by pharmacist id
    public function getBox($id)
    {
        $pharmacist = Pharmacist::query()->find($id);
        if ($pharmacist == null) {
            return response([
                'message' => 'Invalid ID'
            ], 422);
        }
        $pharmacist = Pharmacist::query()
            ->where('id', '=', $id)
            ->select('financial_fund')->get();
        return response()->json(['the financial fund' => $pharmacist], 200);
    }

//-------------------------------------------------------------------------------------------------
    //show a specific profile
    public function index($id)
    {
        $pharmacist = Pharmacist::query()->where('id', $id)->get();
        return response()->json(['message' => 'this is my profile', $pharmacist], 200);
        // return response()->json($pharmacist, 200);
    }

//-------------------------------------------------------------------------------------------------

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
            'registration_number' => 'required|numeric|min:4',
            'registration_date' => ['required'],
            'released_on_date' => ['required'],
            'city' => ['required', 'string', 'max:30'],
            'region' => ['required', 'string', 'max:30'],
            'name_of_pharmacy' => ['required', 'string', 'max:30'],
            'landline_phone_number' => 'required|numeric|min:10',
            'mobile_number' => 'required|numeric|min:10',
            'copy_of_the_syndicate_card_url' => 'required|file',//'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'string', 'min:8'],
            'password_confirmation' => ['required_with:password', 'same:password', 'string', 'min:8'],
            'image_url' => 'required|file',//'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'financial_fund' => 'required|numeric',
//            'active' => 'required',
            'FCM_token' => 'required',

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
            'active' => $request->active,
            'FCM_token'=>$request->FCM_token,

        ]);

        $authToken = $pharmacist->createToken('auth-token')->plainTextToken;

        return response()->json([
            'The Pharmacist' => $pharmacist,
            'Token' => $authToken,
            'message' => 'Done'
        ], 200);
    }

//-------------------------------------------------------------------------------------------------

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
        $pharmacist = Pharmacist::query()->where('email', '=', $request->email)->first();

        if (!$pharmacist) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $authToken = $pharmacist->createToken('auth-token')->plainTextToken;
        return response()->json([
            //'Pharmacist' => $pharmacist,
            'access_token' => $authToken,
            'message' => 'Done'

        ]);
    }

//-------------------------------------------------------------------------------------------------

    //update pharmacist information
    public function update(Request $request, int $id)
    {
        $pharmacist = Pharmacist::query()->find($id);
        if ($pharmacist == null) {
            return response([
                'message' => 'Invalid ID'
            ], 422);
        }
        $city = $request->input('city');
        $region = $request->input('region');
        $name_of_pharmacy = $request->input('name_of_pharmacy');
        $landline_phone_number = $request->input('landline_phone_number');
        $mobile_number = $request->input('mobile_number');


        if ($request->hasFile('image_url')) {
            $destination_path = 'public/files/images';
            $image_url = $request->file('image_url');
            $file_name = $image_url->getClientOriginalName();
            $path = $request->file('image_url')->storeAs($destination_path, $file_name);
            $url = Storage::url($path);
            $pharmacist->image_url = $url;
        }
//        if ($request->has('financial_fund')) {
//            $old_fund = $pharmacist->financial_fund;
//            $new_fund = (float)$request->input('financial_fund');
//
//            $pharmacist->financial_fund = $old_fund + $new_fund;
//        }
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


        $pharmacist->save();
        return response()->json([
            'message' => 'Updated Successfully', $pharmacist
        ]);

    }

//-------------------------------------------------------------------------------------------------

    //change password
    public function change_password(Request $request, $id)
    {
        $pharmacist = Pharmacist::query()->find($id);
        if ($pharmacist == null) {
            return response([
                'message' => 'Invalid ID'
            ], 422);
        }
        $request->validate([
            'password' => ['required', 'confirmed', 'string', 'min:8'],
            'password_confirmation' => ['required_with:password', 'same:password', 'string', 'min:8'],
        ]);
        $password = Pharmacist::query()->update([
            'password' => bcrypt(request('password')),
            'password_confirmation' => bcrypt(request('password_confirmation')),
        ]);
        return response()->json([
            'message' => 'updated successfully',
            $password
        ]);
    }

//-------------------------------------------------------------------------------------------------

    /*  //log out by delete token
       public function logout(Request $request)
      {
          $deleted = $request->user()->currentAccessToken()->delete();
          return $deleted == '1' ? response()->json(['message' => 'Deleted']) : $deleted;
      }*/

//-------------------------------------------------------------------------------------------------

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

//-------------------------------------------------------------------------------------------------

    //search product or medicine by name
    public function searchByName($name)
    {
        $medicines = DB::table('medicines')->where('trade_name', $name)->get();
        $products = DB::table('products')->where('name', $name)->get();

        $results = [];

        if (!$medicines->isEmpty()) {
            $results['search'] = $medicines;
        }

        if (!$products->isEmpty()) {
            $results['search'] = $products;
        }

        if (!empty($results)) {
            return response()->json(['message' => $results], 200);
        } else {
            return response()->json(['message' => 'No matching medicine or product found'], 404);
        }

    }

//-------------------------------------------------------------------------------------------------

    //search product or medicine by barcode
    public function searchByBarcode($barcode)
    {
        $medicines = DB::table('medicines')->where('barcode', $barcode)->get();
        $products = DB::table('products')->where('barcode', $barcode)->get();

        $response = [];

        if (!$medicines->isEmpty()) {
            $response['search'] = $medicines;
        }

        if (!$products->isEmpty()) {
            $response['search'] = $products;
        }

        if (!empty($response)) {
            return response()->json(['message' => $response], 200);
        } else {
            return response()->json(['message' => 'No matching medicine or product found'], 404);
        }
    }

//-------------------------------------------------------------------------------------------------

    //display all notification
    public function showNotification($id)
    {
        $pharmacist = Notification::query()->where('pharmacist_id', '=', $id)->first();
        if ($pharmacist == null) {
            return response([
                'message' => 'Invalid ID'
            ], 422);
        }
        $notification = Notification::query()->where('pharmacist_id', '=', $id)
            ->select('pharmacist_id', 'title', 'body')->get();
        return response()->json(['message' => $notification]);

    }

//-------------------------------------------------------------------------------------------------

    //get active pharmacist
    public function getActive($id)
    {
        $active = Pharmacist::query()->where('id', '=', $id)->first();
        if ($active == null) {
            return response()->json(['message' => 'invalid id'], 422);
        }
        $pharmacist = Pharmacist::query()
            ->where('id', '=', $id)
            ->select('active')->get();
        return response()->json(['status of pharmacist' => $pharmacist], 200);


    }

}
