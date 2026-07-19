<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;


use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\LoginRequest;

use App\Services\AuthenticationService;

use App\Models\User;

class AuthController extends Controller
{



    public function __construct(
        protected AuthenticationService $authService
    ) {}








    public function register(RegisterRequest $request)
    {

        $result = $this->authService->register($request->validated());



        return response()->json([
            'message' => 'Account Created Successfuly',
            'token' => $result['token'],
            'user' => $result['user']
        ], 200);
    }

    public function login(LoginRequest $request)
    {


        $result = $this->authService->login($request->email, $request->password);

        $user = $result['user'];
        $token = $result['token'];

        return response()->json([
            'message' => 'Login Successfuly',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ], 200);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json([
            'message' => 'Logout Successfuly'
        ], 200);
    }
}
