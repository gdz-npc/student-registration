<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; 
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailOtpController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([ 
            'email' => 'required|email|max:255', 
        ]); 
        $email = Str::lower(trim($data['email']));  
        $otp = (string) random_int(100000, 999999); 
        $key = 'email_otp:' . $email; 

                Cache::store('file')->put( 
            $key,             $otp,             now()->addMinutes(5) 
        ); 
        try { 
            Mail::raw( 
                "Your email verification code is {$otp}. "                 . "This code expires in 5 minutes.",                 function ($message) use ($email) { 
                    $message->to($email) 
                        ->subject('Your Email Verification Code'); 
                } 
            );
            } catch (\Throwable $e) { 
            // Remove the saved OTP if email delivery fails.
            Cache::store('file')->forget($key);

            report($e);

             return response()->json([ 
                'message' => 'Unable to send the verification email. Please try again.', 
                'error' => $e->getMessage(),
            ], 500); 
        } 
         return response()->json([ 
            'message' => 'Verification code sent successfully.', 
            'expires_in_minutes' => 5, 
        ], 200); 
    } 
 
    /** 
     * Verify the submitted OTP. 
     */     public function verify(Request $request): JsonResponse 
    { 
        $data = $request->validate([ 
            'email' => 'required|email|max:255', 
            'otp' => [ 
                'required', 
                'string', 
                'size:6', 
                'regex:/^[0-9]{6}$/', 
            ], 
        ]); 
 
        $email = Str::lower(trim($data['email'])); 
 
        $key = 'email_otp:' . $email; 
 
        // Retrieve the code from the SAME file cache store. 
        $savedOtp = Cache::store('file')->get($key); 
 
        // Reject missing, expired, or incorrect codes.
        if (
            $savedOtp === null || 
            !hash_equals((string) $savedOtp, $data['otp']) 
        ) {
            return response()->json([ 
                'message' => 'The code is invalid or has expired.', 
            ], 422); 
        } 
         Cache::store('file')->forget($key); 
         
         return response()->json([ 
            'message' => 'Email verified successfully.', 
        ], 200); 

    }
}
