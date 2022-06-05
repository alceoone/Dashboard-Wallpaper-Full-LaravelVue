<?php

namespace App\Http\Controllers;

use App\User;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $accessToken = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $users = JWTAuth::user()
        ->leftJoin('icons', 'users.id_icons', '=', 'icons.id_icons')
        ->select(
            'users.id_user as id',
            'users.name as user',
            'users.email as email',
            'users.role as role',
            'icons.url as photo'
            )
        ->where('users.email', '=', $request->only('email'))
        ->get()->first();
       
        return response()->json([
            'success'   => true,
            'accessToken' => $accessToken,
            'userData' => $users
        ]);
    }
}
