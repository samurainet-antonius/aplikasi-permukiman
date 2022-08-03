<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = JWTAuth::attempt($validator->validated())) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid email and password.', 'error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    protected function createNewToken($token){
        return response()->json([
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'data' => auth()->user(),
            'roles' => auth()->user()->roles[0]->name
        ]);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }


    /**
     * Get the Auth user using token.
     * @return \Illuminate\Http\JsonResponse
     */
    public function user() {

        if(auth()->user()) {
            return response()->json([
                'status' => 200,
                'data' => auth()->user(),
            ]);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Invalid token.', 'error' => 'Unauthorized'], 401);
        }
    }

    public function logout() {
        auth()->logout();
        return response()->json(['status' => 'success', 'message' => 'User logged out successfully']);
    }

    public function cekUpload(Request $request)
    {
        try {
            $foto = $request->image;
            $fileName = time() . '.' . $foto->getClientOriginalExtension();
            $folder = 'file/pembaruan';
            $foto->move(public_path($folder), $fileName);
        } catch (\Exception $e) {
            echo $e;
        }

        return response()->json(['status' => 'success', 'message' => 'Upload successfully']);
    }
}
