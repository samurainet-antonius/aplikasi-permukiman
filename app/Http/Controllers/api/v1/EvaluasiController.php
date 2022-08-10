<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Models\District;

class EvaluasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function index()
    {
        $user = Auth::id();
        $users = User::find($user);

        $role = $users->roles[0]->name;
        $petugas = Petugas::where('users_id', $user)->first();

        $village = [];

        switch ($role) {
            case "admin-provinsi":
            case "admin-kabupaten":
                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();

                break;
            case "admin-kecamatan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('district_code', $district[0]->code)->get();

                break;
            case "admin-kelurahan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('code', $petugas->village_code)->get();

                break;
            default:
                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();
                $villages = Village::select('code', 'name')->where('district_code', $district[0]->code)->first();
        }
    }

    public function create()
    {
        $user = Auth::id();
        $users = User::find($user);

        $role = $users->roles[0]->name;
        $petugas = Petugas::where('users_id', $user)->first();

        $village = [];

        switch ($role) {
            case "admin-provinsi":
            case "admin-kabupaten":
                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->orderBy('name', 'ASC')->get();

                break;
            case "admin-kecamatan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('district_code', $district[0]->code)->orderBy('name', 'ASC')->get();

                break;
            case "admin-kelurahan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('code', $petugas->village_code)->get();

                break;
            default:
                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();
                $village = Village::select('code', 'name')->where('district_code', $district[0]->code)->orderBy('name', 'ASC')->get();

        }

        $years = [];
        for($i= date("Y"); $i>="2015"; $i--) {
            $years[] = "$i";
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'kecamatan' => $district,
                'desa' => $village,
                'tahun' => $years,
            ]
        ]);

    }

    public function filterVillage(Request $request)
    {
        $village = Village::select('code', 'name')->where('district_code', $request->district)->orderBy('name', 'ASC')->get();

        return response()->json([
            'status' => 200,
            'data' => $village
        ]);
    }
}
