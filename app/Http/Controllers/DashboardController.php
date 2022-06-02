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
                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();

                $selectDistrict = '1';
                $selectVillage = '1';

                $textDistrict = 'Semua Kecamatan ';
                $textVillage = 'Semua Desa ';

                if ($request->district_code == 'semua') {
                    $village = '';
                } elseif ($request->district_code) {
                    $village = Village::select('code', 'name')->where('district_code', $request->district_code)->get();
                }

                $req['district'] = 'semua';
                $req['village'] = 'semua';
                $req['years'] = '5';
                break;
            case "admin-kecamatan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('district_code', $district[0]->code)->get();

                $textDistrict = 'Kecamatan ' . $district[0]->name . ' ';
                $textVillage = 'Semua Desa ';

                $selectDistrict = '0';
                $selectVillage = '1';

                $req['district'] = $petugas->district_code;
                $req['village'] = 'semua';
                $req['years'] = '5';

                break;
            case "admin-kelurahan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('code', $petugas->village_code)->get();

                $textDistrict = 'Kecamatan ' . $district[0]->name . ' ';
                $textVillage = 'Desa '. $village[0]->name;

                $selectDistrict = '0';
                $selectVillage = '0';

                $req['district'] = $petugas->district_code;
                $req['village'] = $petugas->village_code;
                $req['years'] = '5';
                break;
            default:
                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();

                $selectDistrict = '1';
                $selectVillage = '1';

                $textDistrict = 'Semua Kecamatan ';
                $textVillage = 'Semua Desa ';

                if($request->district_code == 'semua') {
                    $village = '';
                } elseif($request->district_code) {
                    $village = Village::select('code', 'name')->where('district_code', $request->district_code)->get();
                }

                $req['district'] = 'semua';
                $req['village'] = 'semua';
                $req['years'] = '5';
        }

        if($request->district_code == 'semua') {
            $textDistrict = 'Semua Kecamatan ';
        } elseif($request->district_code) {
            $dis = District::where('code', $request->district_code)->first();
            $textDistrict = 'Kecamatan ' . $dis->name . ' ';
            $req['district'] = $request->district_code;
        }

        if ($request->village_code == 'semua') {
            $textVillage = 'Semua Desa ';
        } elseif ($request->village_code) {
            $dis = Village::where('code', $request->village_code)->first();
            $textVillage = 'Desa ' . $dis->name . ' ';
            $req['village'] = $request->village_code;
        }

        if($request->years) {
            $textYears = 'Dalam '. $request->years.' Tahun';
            $req['years'] = $request->years;
        } else {
            $textYears = 'Dalam 5 Tahun';
        }

        $evaluasi = Evaluasi::whereNotNull('status_id')->get();

        $year = 5;
        if($request->years) {
            $year = $request->years;
        }

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

                $eval = Evaluasi::whereNotNull('status_id')
                    ->where('province_code', 12)
                    ->where('city_code', 1207);

                if($request->district_code != 'semua' ) {
                    $eval = $eval->where('district_code', $request->district_code);
                }

                if ($request->village_code != 'semua') {
                    $eval = $eval->where('village_code', $request->village_code);
                }

                $eval = $eval->where('status_id', $val->id)
                    ->where('tahun', $item)
                    ->count();

                $series[] = $eval;
            }

            $data[] = [
                'name' => $val->nama,
                'data' => $series
            ];
        }

        $pie = [];
        foreach($years as $key => $value) {

            $y = [];
            foreach($status as $val) {

                $eval = Evaluasi::whereNotNull('status_id')
                    ->where('province_code', 12)
                    ->where('city_code', 1207);

                if ($request->district_code != 'semua') {
                    $eval = $eval->where('district_code', $request->district_code);
                }

                if ($request->village_code != 'semua') {
                    $eval = $eval->where('village_code', $request->village_code);
                }

                $eval = $eval->where('status_id', $val->id)
                    ->where('tahun', $value)
                    ->count();

                $y[] = [
                    'name' => $val->nama,
                    'y' => $eval,
                    'id' => $val->id
                ];
            }

            $pie[$value] = $y;
        }

        $select = [
            'district' => $selectDistrict,
            'village' => $selectVillage
        ];

        $text = [
            'district' => $textDistrict,
            'village' => $textVillage,
            'years' => $textYears
        ];

        return view('dashboard', compact('district', 'village', 'data', 'years', 'select', 'pie', 'text', 'req'));
    }

    public function detail(Request $request, $district_code, $village_code, $years, $status_id)
    {
        $search = $request->get('search', '');

        $evaluasi = Evaluasi::whereNotNull('status_id')
            ->where('province_code', 12)
            ->where('city_code', 1207);

        if ($district_code != 'semua') {
            $evaluasi = $evaluasi->where('district_code', $district_code);
        }

        if ($village_code != 'semua') {
            $evaluasi = $evaluasi->where('village_code', $village_code);
        }

        $evaluasi = $evaluasi->where('status_id', $status_id)
            ->where('tahun', $years)
            ->search($search)
            ->paginate(5);

        $status = StatusKumuh::find($status_id);

        return view('dasbor-detail', compact('evaluasi', 'status', 'years'));
    }
}
