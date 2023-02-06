<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Bad Request', 'validation' => $validator->messages()], 400);
        }

        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register an User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Bad Request', 'validation' => $validator->messages()], 400);
        }

        $credentials = request([
            'name',
            'email',
            'phone_number',
            'role'
        ]);

        try {
            $user = User::create([...$credentials, 'password' => Hash::make(request('password'))]);
            if (!$user) {
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            if (!$token = auth('api')->attempt(request(['email', 'password']))) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Internal Server Error', "message" => $th->getMessage()], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth('api')->user();
        $user->profile_picture =  asset('img/user') . '/' . $user->profile_picture;
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function updateProfile()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'password' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Bad Request', 'validation' => $validator->messages()], 400);
        }

        try {
            $user = User::findOrFail(auth('api')->user()->id);
            $user->update([
                'name' => request('name'),
                'email' => request('email'),
                'phone_number' => request('phone_number'),
                'password' => request()->has('password') ? Hash::make(request('password')) : $user->password,
            ]);

            return response()->json(['message' => 'Updated Successfully', 'data' => $user], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function updatePhoto()
    {
        $validator = Validator::make(request()->all(), [
            'profile_picture' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Bad Request', 'validation' => $validator->messages()], 400);
        }

        $user = User::findOrFail(auth('api')->user()->id);

        if (request()->file('profile_picture')) {
            if ($user->profile_picture != 'sample.jpg') {
                File::delete(public_path('/img/user/') . $user->profile_picture);
            }
            $image_name = time() . rand(100, 999) . '.' . request()->file('profile_picture')->getClientOriginalExtension();
            request()->file('profile_picture')->move(public_path('/img/user'), $image_name);
        }

        $user->update([
            'profile_picture' => $image_name
        ]);

        return response()->json(['message' => 'Updated Successfully', 'data' => $user], 200);
    }
}
