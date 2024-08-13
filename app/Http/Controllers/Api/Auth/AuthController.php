<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {

        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $token = $user->createToken('user')->plainTextToken;

        $user['token'] = $token;

        return response()->json([
            // 'data'      => $user,
            'success'   => "true"
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {

            $user = Auth::user();

            $token = $user->createToken('user')->plainTextToken;

            $user['token'] = $token;

            return response()->json([
                'data'      => $user,
                'success'   => "true"
            ], 200);
        } else {

            return response()->json([

                'error'   => "unauthorized"

            ], 401);
        }
    }

    public function logout(Request $request)
    {

        $user = $request->user();

        $user->tokens()->delete();

        return response()->json([
            'success'   => "true"
        ], 200);
    }
}
