<?php

namespace App\Http\Controllers;

use App\Http\Mailer\SendEmailRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;



class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100|unique:users',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'we can not find a user with that email address'], 404);
        }
        $token = Auth::fromUser($user);

        if ($user) {
            $sendEmail = new SendEmailRequest();
            $sendEmail->sendEmail($user->email, $token);
        }

        Log::info('Forgot PassWord Link : ' . 'Email Id :' . $request->email);
        return response()->json(['message' => 'password reset link genereted in mail'], 200);
    }

    public function resetPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'new_password' => 'min:6|required|',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => "Password doesn't match"
            ], 400);
        }
        $user = Auth::user();

        $user = User::where('email', $user->email)->first();

        if (!$user) {
            Log::error('Email not found.', ['id' => $request->email]);
            return response()->json([
                'message' => "we can't find the user with that e-mail address"
            ], 400);
        } else {
            $user->password = bcrypt($request->new_password);
            $user->save();
            Log::info('Reset Successful : ' . 'Email Id :' . $request->email);

            return response()->json([
                'status' => 201,
                'message' => 'Password reset successfull!'
            ], 201);
        }
    }    
}
