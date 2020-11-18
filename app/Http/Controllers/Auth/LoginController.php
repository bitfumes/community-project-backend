<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Lang;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $this->isUserValid($user, $request->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response([
            'message' => Lang::get('api.login_successful'),
            'data' => new UserResource($user),
            'access_token' => $user->createToken($request->email)->plainTextToken
        ], Response::HTTP_OK);
    }

    public function isUserValid($user, $password)
    {
        return $user && Hash::check($password, $user->password);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => Lang::get('api.logout_successful')
        ], Response::HTTP_OK);
    }
}
