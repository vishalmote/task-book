<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\Validator;
use Throwable;

class PAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 'error',
                'data' => ['validationErrorList' => $errors]
            ], 200);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('passportToken')->accessToken;

        return response()->json([
            'status' => 'success',
            'data' => ['token' => $token]
        ], 200);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 'error',
                'data' => ['validationErrorList' => $errors]
            ], 200);
        }
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('passportToken')->accessToken;
            return response()->json([
                'status' => 'success',
                'data' => ['token' => $token]
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => ['errorList' => ['Invalid Credentials']]
            ], 200);
        }
    }

    public function me()
    {
        $user = auth()->user();
        if ($user) {
            return response()->json([
                'status' => 'success',
                'data' => ['user' => $user]
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => ['errorList' => ['unauthorized' => 'unauthorized']]
            ], 200);
        }
    }
}
