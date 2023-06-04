<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\Pharmacist;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    private Otp $otp;

    public function __construct()
    {
        $this->otp = new Otp();
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $otp2 = $this->otp->validate($request->email, $request->otp);
        if (!$otp2 ->status){
            return response()->json(['error' => $otp2] , 401);
        }
        $pharmacist = Pharmacist::query()->where('email' , $request->email)->first();
        $pharmacist->update(['password' => Hash::make($request->password)]);
        $pharmacist->tokens()->delete();
        $success['success'] = 'updated successfully';
        return response()->json($success , 200);

    }
}
