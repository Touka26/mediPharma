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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

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
            //'pharmacist_id' => ['required', 'integer'],
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
            'image_url' =>'required|file' ,//'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'financial_fund' => ['required'],
        ]);

         if ($request->hasFile('copy_of_the_syndicate_card_url')) {
             $destination_path = 'public/files/syndicateCard';
             $copy_of_the_syndicate_card_url= $request->file('copy_of_the_syndicate_card_url');
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
            //'pharmacist_id' => $request->pharmacist_id,
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
            'copy_of_the_syndicate_card_url' => $url1,//file('copy_of_the_syndicate_card_url')->store('syndicateCard'),
            'email' => $request->email,
            'password' => bcrypt(request('password')),
            'password_confirmation' => bcrypt(request('password_confirmation')),
            'image_url' => $url,//file('image_url')->store('images'),
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

        if (!$pharmacistLogin|| !Hash::check($request->password, $pharmacistLogin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $authToken = $pharmacistLogin->createToken('auth-token')->plainTextToken;
        return response()->json([
            'Pharmacist' => $pharmacistLogin,
            'access_token' => $authToken,
            'message' => 'Done'

        ]);
    }

    //----------------------------------------------------------------------

    public function logout(Request $request){
        $deleted = $request->user()->currentAccessToken()->delete();
        return $deleted == '1' ? response()->json(['message' => 'Deleted']) : $deleted;

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
