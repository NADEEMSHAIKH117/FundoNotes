<?php

namespace App\Http\Controllers;

use App\Exceptions\FundoNoteException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

/* 
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
     * @OA\Post(
     *   path="/api/auth/register",
     *   summary="register",
     *   description="register the user for login",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"firstname","lastname","email", "password", "password_confirmation"},
     *               @OA\Property(property="firstname", type="string"),
     *               @OA\Property(property="lastname", type="string"),
     *               @OA\Property(property="email", type="string"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="password_confirmation", type="password")
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200, description="User successfully registered"),
     *   @OA\Response(response=401, description="The email has already been taken"),
     * )


     * It takes a POST request and required fields for the user to register
     * and validates them if it validated, creates those field including 
     * values in DataBase and returns success response
     *
     *@return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                throw new FundoNoteException("The email has already been taken", 401);
            }

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            Cache::remember('users', 3600, function () {
                return DB::table('users')->get();
            });
        } catch (FundoNoteException $e) {

            return response()->json(['message' => $e->message(), 'status' => $e->statusCode()]);
        }
        return response()->json([
            'message' => 'User successfully registered',
        ], 200);
    }

    /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   summary="login",
     *   description=" login ",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="string"),
     *               @OA\Property(property="password", type="password"),
     *            ),
     *        ),
     *    ),
     * @OA\Response(response=200, description="Login successfull"),
     * @OA\Response(response=401, description="email not found register first"),
     * 
     * )
     * Takes the POST request and user credentials checks if it correct,
     * if so, returns JWT access token.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        Cache::remember('users', 3600, function () {
            return DB::table('users')->get();
        });

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            Log::error('User failed to login.', ['id' => $request->email]);
            return response()->json([
                'message' => 'email not found register first'
            ], 401);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Log::info('Login Success : ' . 'Email Id :' . $request->email);
        return response()->json([
            'access_token' => $token,
            'message' => 'Login successfull'
        ], 200);
    }


    /**   
     *
     * Takes the GET request and JWT access token to show the user profile
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get(
     *   path="/api/auth/userProfile",
     *   summary="userProfile",
     *   description="userProfile ",
     *   @OA\RequestBody(      
     *    ),
     *   @OA\Response(response=404, description="Invalid authorization token"),
     *   security={
     *       {"Bearer": {}}
     *     }
     * )
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Takes the POST request and JWT access token to logout the user profile
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *   path="/api/auth/logout",
     *   summary="logout",
     *   description=" logout ",
     *  @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"token",},
     *               @OA\Property(property="token", type="string"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="User successfully signed out"),
     * )
     */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'User successfully logget out',
        ], 201);
    }

    /**
     * @OA\Post(
     *   path="/api/auth/addProfileImage",
     *   summary="Add profile",
     *   description="user profile image",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"image"},
     *               @OA\Property(property="image", type="file"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Profilepic Successsfully Added"),
     *   @OA\Response(response=401, description="We cannot find a user"),
     *   security={
     *       {"Bearer": {}}
     *     }
     * )
     * 
     * This function will take image
     * as input and save in AWS S3
     * and will save link in database
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProfileImage(Request $request)
    {
        $request->validate([

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        $user = Auth::user();

        $user = User::where('email', $user->email)->first();
        if ($user) {

            $path = Storage::disk('s3')->put('images', $request->image);
            $url = env('AWS_URL') . $path;
            User::where('email', $user->email)
                ->update(['profilepic' => $url]);
            return response()->json(['message' => 'Profilepic Successsfully Added', 'URL' => $url], 201);
        } else {
            return response()->json(['message' => 'We cannot find a user'], 400);
        }
    }


    /**
     * @OA\Post(
     *   path="/api/auth/updateProfileImage",
     *   summary="update profile",
     *   description="update profile image",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"image"},
     *               @OA\Property(property="image", type="file"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Profilepic Successsfully update"),
     *   @OA\Response(response=401, description="We cannot find a user"),
     *   security={
     *       {"Bearer": {}}
     *     }
     * )
     * 
     * This function will take image
     * as input and update image in AWS S3
     * and will save link in database
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfileImage(Request $request)
    { {
            $request->validate([

                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            ]);
            $user = JWTAuth::user();

            $user = User::where('email', $user->email)->first();
            if ($user) {
                $profile_pic = $user->profilepic;
                if ($request->image) {
                    $path = str_replace(env('AWS_URL'), '', $user->profilepic);

                    if (Storage::disk('s3')->exists($path)) {
                        Storage::disk('s3')->delete($path);
                    }
                    $path = Storage::disk('s3')->put('images', $request->image);
                    $pathurl = env('AWS_URL') . $path;
                    $user->profilepic = $pathurl;
                    $user->save();
                }
                return response()->json([
                    'piv' => $profile_pic,
                    'message' => 'Profilepic Successsfully update', 'URL' => $pathurl
                ], 201);
            } else {
                return response()->json(['message' => 'We cannot find a user'], 400);
            }
        }
    }

    /**
     * @OA\Delete(
     *   path="/api/auth/deleteProfileImage",
     *   summary="delete profile",
     *   description="delete profile image",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Profilepic Deleted Successsfully"),
     *   @OA\Response(response=401, description="We cannot find a user"),
     *   security={
     *       {"Bearer": {}}
     *     }
     * )
     * 
     * This function will delete profile
     * image in AWS S3
     * and will save link in database
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProfileImage()
    {
        $user = JWTAuth::user();

        $user = User::where('email', $user->email)->first();
        if ($user) {
            $path = str_replace(env('AWS_URL'), '', $user->profilepic);

            if (Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->delete($path);
            }
            // $user->delete();
            return response()->json([
                'message' => 'Profilepic Deleted Successsfully'
            ], 201);
        } else {
            return response()->json(['message' => 'We cannot find a user'], 400);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}
