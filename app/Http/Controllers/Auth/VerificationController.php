<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lang;

class VerificationController extends Controller
{
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return response([
            'message' => Lang::get('api.verification_successful')
        ], Response::HTTP_OK);
    }

    public function send(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response([
            'message' => Lang::get('api.verification_notification_sent')
        ], Response::HTTP_OK);
    }
}
