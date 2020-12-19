<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','test']]);
    }

    /**
     * Store a new user.
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request
        $rules = [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->responseValidationError($validator->messages());
        }

        try {
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();


            $this->response = [
                'status' => true,
                'message' => trans('api.login'),
                'data' => $user
            ];

            return $this->responseSuccess($this->response);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        //validate incoming request

        $rules = [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return $this->responseValidationError($validator->messages());
        }
        try {

            $credentials = $request->only(['email', 'password']);

            if (!$token = Auth::attempt($credentials)) {

                return $this->responseError(trans('api.UNAUTHORIZED'));
            }
            $data = $this->respondWithToken($token);

            $this->response = [
                'status' => true,
                'message' => trans('api.login'),
                'data' => $data
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    /**
     * Get user details.
     *
     * @param Request $request
     * @return Response
     */
    public function profile()
    {
        try {
            $data = auth()->user();

            $this->response = [
                'status' => true,
                'message' => trans('api.user_details'),
                'data' => $data
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();

            $this->response = [
                'status' => true,
                'message' => trans('api.logout'),
                'data' => null
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }

    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $data = $this->respondWithToken(auth()->refresh());

            $this->response = [
                'status' => true,
                'message' => trans('api.refresh'),
                'data' => $data
            ];

            return $this->responseSuccess($this->response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseInternalError(trans('api.ERROR'));
        }
    }

    /**
     * @param Request $token
     * Respond with token user data.
     * Private Method
     * @return array
     */

    private function respondWithToken($token)
    {
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ];
    }
}
