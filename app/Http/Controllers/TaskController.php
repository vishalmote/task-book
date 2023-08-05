<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use \Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Registration
     */
    public function createTask(Request $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return response()->json(['token' => $user], 200);
    }

    /**
     * Login
     */
    public function listTask(Request $request)
    {
        return 'success';
    }
}
