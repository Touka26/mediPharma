<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Models\Pharmacist;
use App\Notifications\ResetPasswordVerificationNotification;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request){
        $input = $request->only('email');
        $pharmacist = Pharmacist::query()->where('email' , $input)->first();
        $pharmacist->notify(new ResetPasswordVerificationNotification());
        $success['success'] = 'show your email';
        return response()->json($success,200);
    }
}
