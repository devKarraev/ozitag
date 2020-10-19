<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create user.
     *
     * @param AuthRegisterRequest $request
     * @param String  userName
     * @param String  email
     * @param String  password
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(AuthRegisterRequest $request)
    {
        $user = new User([
            'name'     => $request->userName,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        return response()->json([
            'success' => true,
            'id'      => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
        ], 201);
    }

    /**
     * Authorize user.
     *
     * @param  AuthLoginRequest $request
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized Access, please confirm credentials or verify your email'
            ], 401);

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();
        return response()->json([
            'success' => true,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ], 201);
    }

    /**
     * Logout user (Revoke the token).
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
