<?php

namespace App\Http\Controllers;

use App\Models\Evaluasi;
use App\Models\EvaluasiDetail;
use App\Models\City;
use App\Models\Districts;
use App\Models\Village;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EvaluasiStoreRequest;
use App\Models\Petugas;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravolt\Indonesia\Models\District;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::id();
        $users = User::find($user);

        $role = $users->roles[0]->name;
        $petugas = Petugas::where('users_id', $user)->first();

        $village = '';

        switch ($role) {
            case "admin-provinsi":
            case "admin-kabupaten":
                $district = District::select('code', 'name')->where('city_code', '1207')->get();
                // $village = Village::select('code', 'name')->where('district_code', $petugas->district_code)->get();
                break;
            case "admin-kecamatan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                // $village = Village::select('code', 'name')->where('district_code', $petugas->district_code)->get();
                break;
            case "admin-kelurahan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('code', $petugas->village_code)->get();
                break;
            default:
                $district = District::select('code', 'name')->where('city_code', '1207')->get();
                // $village = Village::select('code', 'name')->where('district_code', $petugas->district_code)->get();

        }

        // dd($users->roles[0]->name);

        return view('dashboard', compact('district', 'village'));
    }
}
