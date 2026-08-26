<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                return $this->error($validator->errors()->first(),'Validation error',422);
            }
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return $this->error("Unauthorized", 'Unauthorized', 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->success(['token' => $token, 'user' => $user],'Login successfully',200);

        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), 'Something went wrong',500);
        }
    }
}
