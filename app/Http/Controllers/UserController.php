<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * This controller is for user registration, login, logout
 * and emailverify
 */

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a User.
     * path="api/register",
     * summary="register",
     * description="register the user for login",
     * required=("firstname","lastname", "email", "password", "confirm_password")
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        return response()->json([
            'message' => 'User successfully registered',
        ], 201);
    }

    /**
     * Get a JWT via given credentials.
     * Login a user
     * path="api/login",
     * summary="login"
     * description="user login",s
     * required=("email","password")
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    /**
     * UserProfile
     * path="api/userProfile"
     * summary="profile"
     * description="user profile"
     * required="Token which is generated after login"
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * logout function
     * path="api/logout"
     * summary="logout"
     * description="user logout"
     * required="Token which is generated after login to logout the user"
     */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'User successfully logget out',
        ], 201);
    }


    /**
     * Get the token array structure.
     * function for creating the token
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
