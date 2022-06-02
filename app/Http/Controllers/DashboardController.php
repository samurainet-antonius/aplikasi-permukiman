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
use App\Models\StatusKumuh;
use App\Models\User;
use Carbon\Carbon;
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

        $evaluasi = Evaluasi::whereNotNull('status_id')->get();
        $year = 5;

        $years[] = (int) date('Y');
        for ($i=1; $i < $year; $i++) {
            $years[] = date('Y') - $i;
        }

        $years = array_reverse($years);

        $status = StatusKumuh::all();

        $data = [];
        foreach($status as $key => $val) {

            $series = [];
            foreach($years as $item) {
                $eval = Evaluasi::whereNotNull('status_id')->where('status_id', $val->id)->where('tahun', $item)->count();
                $series[] = $eval;
            }

            $data[] = [
                'name' => $val->nama,
                'data' => $series
            ];
        }

        // dd($data);
        // dd($evaluasi->toArray());


        return view('dashboard', compact('district', 'village', 'data', 'years'));
    }
}
