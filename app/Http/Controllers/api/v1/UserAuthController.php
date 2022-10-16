<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Village;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Models\District;

class UserAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = JWTAuth::attempt($validator->validated())) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid email and password.', 'error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL(),
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
    public function user()
    {
        if (auth()->user()) {
            $id = auth()->user()->id;
            $user = User::find($id);
            $role = $user->roles[0]->name;
            $petugas = Petugas::where('users_id', $id)->first();

            switch ($role) {
                case "admin-kecamatan":
                    $dataPetugas = District::where('code', $petugas->district_code)->first();
                    $text = ucwords(strtolower($dataPetugas->name));
                    $user->petugas = 'Petugas Kecamatan';
                    $user->desa = $text;
                    break;
                case "admin-kelurahan":
                    $dataPetugas = Village::where('code', $petugas->village_code)->first();
                    $text = ucwords(strtolower($dataPetugas->name));
                    $user->petugas = 'Petugas Desa';
                    $user->desa = $text;
                    break;
            }

            return response()->json([
                'status' => 200,
                'data' => $user,
            ]);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Invalid token.', 'error' => 'Unauthorized'], 401);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['status' => 'success', 'message' => 'User logged out successfully']);
    }

    public function cekUpload(Request $request)
    {
        try {
            if ($request->image1) {
                $foto1 = $request->image1;
                $fileName1 = time() . '-1' . '.' . $foto1->getClientOriginalExtension();
                $folder = 'file/pembaruan';
                $foto1->move(public_path($folder), $fileName1);
            }

            if ($request->image2) {
                $foto2 = $request->image2;
                $fileName2 = time() . '-2' . '.' . $foto2->getClientOriginalExtension();
                $folder = 'file/pembaruan';
                $foto2->move(public_path($folder), $fileName2);
            }
        } catch (\Exception $e) {
            echo $e;
        }

        return response()->json(['status' => 'success', 'message' => 'Upload successfully']);
    }
}
