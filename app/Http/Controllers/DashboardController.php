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
use Carbon\CarbonPeriod;
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
            case "bupati":
            case "seksi":
            case "petugas-kabupaten":
            case "kepala-bidang":
            case "kepala-dinas":
                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();
                $villages = Village::select('code', 'name')->where('district_code', $district[0]->code)->first();

                $textDistrict = 'Semua Kecamatan ';
                $textVillage = 'Semua Desa ';

                if ($request->district_code == 'semua') {
                    $village = '';
                } elseif ($request->district_code) {
                    $village = Village::select('code', 'name')->where('district_code', $request->district_code)->get();
                }
                break;
            case "admin-kecamatan":
            case "camat":
            case "petugas-kecamatan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('district_code', $district[0]->code)->get();
                $villages = Village::select('code', 'name')->where('district_code', $district[0]->code)->first();

                $textDistrict = 'Kecamatan ' . $district[0]->name . ' ';
                $textVillage = 'Desa ' . $village[0]->name;
                break;
            case "admin-kelurahan":
            case "lurah":
            case "petugas-kelurahan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('code', $petugas->village_code)->get();
                $villages = Village::select('code', 'name')->where('code', $petugas->village_code)->first();

                $textDistrict = 'Kecamatan ' . $district[0]->name . ' ';
                $textVillage = 'Desa ' . $village[0]->name;
                break;
            default:
                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();
                $villages = Village::select('code', 'name')->where('district_code', $district[0]->code)->first();

                $textDistrict = 'Kecamatan ' . $district[0]->name;
                $textVillage = 'Desa ' . $villages->name;

                if ($request->district_code == 'semua') {
                    $village = '';
                } elseif ($request->district_code) {
                    $village = Village::select('code', 'name')->where('district_code', $request->district_code)->get();
                }
        }

        if ($request->district_code) {
            $dis = District::where('code', $request->district_code)->first();
            $textDistrict = 'Kecamatan ' . $dis->name . ' ';
        }

        if ($request->village_code == 'null') {
            $dis = Village::select('code', 'name')->where('district_code', $request->district_code)->first();
            $textVillage = 'Desa ' . $dis->name . ' ';
        } elseif ($request->village_code) {
            $dis = Village::where('code', $request->village_code)->first();
            $textVillage = 'Desa ' . $dis->name . ' ';
        }

        if ($request->years) {
            $textYears = 'Tahun ' . $request->years;
        } else {
            $textYears = 'Tahun ' . date('Y');
        }

        $text = [
            'district' => $textDistrict,
            'village' => $textVillage,
            'years' => $textYears
        ];

        $tahun = $request->years ? $request->years : date('Y');
        $range = $request->range ? $request->range : 12;

        $month = $this->monthChart($range);

        $evaluasi = Evaluasi::select('id')->where('tahun', $tahun);

        if ($request->district_code) {
            if ($request->district_code != 'semua') {
                $evaluasi = $evaluasi->where('district_code', $request->district_code);
            }
        } else {
            $evaluasi = $evaluasi->where('district_code', $district[0]->code);
        }

        if ($request->village_code || $request->village_code == 'null') {

            if ($request->village_code == 'null') {
                $dis = Village::select('code', 'name')->where('district_code', $request->district_code)->first();
                $evaluasi = $evaluasi->where('village_code', $dis->village_code);
            } elseif ($request->village_code != 'semua') {
                $evaluasi = $evaluasi->where('village_code', $request->village_code);
            }
        } else {
            $evaluasi = $evaluasi->where('village_code', $villages->code);
        }

        $evaluasi = $evaluasi->get();

        $evaluasiId = array_column($evaluasi->toArray(), 'id');
        $query = EvaluasiDetail::select('kriteria_id', 'nama_kriteria')
            ->whereIn('evaluasi_id', $evaluasiId)
            ->groupBy('kriteria_id')
            ->get();

        $data = [];
        $bulan = [];
        foreach ($query as $val) {

            $series = [];
            $bulan = [];
            foreach ($month as $mon) {

                $skor = 0;
                foreach ($evaluasi as $eval) {
                    $evalCount = EvaluasiDetail::where('kriteria_id', $val->kriteria_id)
                        ->where('evaluasi_id', $eval->id)
                        ->where('jawaban', '!=', '')
                        ->whereMonth('created_at', $mon['number'])
                        ->get()->count();

                    if ($evalCount != 0) {
                        $evalNilai = EvaluasiDetail::where('kriteria_id', $val->kriteria_id)
                            ->whereIn('evaluasi_id', $evaluasi)
                            ->where('jawaban', '!=', '')
                            ->whereMonth('created_at', $mon['number'])
                            ->sum('nilai');

                        $nilai = floor($evalNilai / $evalCount);

                        $skor = $skor + $nilai;
                    }
                }

                $evaluasiCheck = EvaluasiDetail::where('kriteria_id', $val->kriteria_id)
                    ->whereIn('evaluasi_id', $evaluasiId)
                    ->where('jawaban', '!=', '')
                    ->whereMonth('created_at', $mon['number'])
                    ->get()->count();

                if ($evaluasiCheck != 0) {
                    $evaluasiCount = $evaluasi->count();

                    $skor = floor($skor / $evaluasiCount);

                    // $skor = ($skor == 0) ? 0.01 : $skor;

                    if ($skor == 0) {
                        $color = '#00ff00';
                    } elseif ($skor == 1) {
                        $color = '#ff8000';
                    } elseif ($skor == 3 || $skor == 2) {
                        $color = '#ffff00';
                    } else {
                        $color = '#ff0000';
                    }

                    $series[] = [
                        'y' => $skor,
                        'color' => $color
                    ];

                    $bulan[] = $mon['text'];
                }
            }

            $data[] = [
                'name' => $val->nama_kriteria,
                'data' => $series
            ];
        }

        if ($request->all()) {
            $req['district'] = $request->district_code;
            $req['village'] = $request->village_code;
            $req['years'] = $request->years;
        } else {
            $req['district'] = null;
            $req['village'] = null;
            $req['years'] = null;
        }

        if (!$data) {
            $data[] = [
                'name' => 'Kosong',
                'data' => [0]
            ];
        }

        $status = StatusKumuh::all();

        return view('dashboard', compact('district', 'village', 'data', 'text', 'bulan', 'status', 'req'));
    }

    public function detail(Request $request, $district, $village, $month, $years)
    {
        $date = Carbon::parse($month);
        $date = $date->format('m');

        $data = Evaluasi::where('district_code', $district)->where('village_code', $village)->get();
        $village = Village::where('code', $village)->first();

        return view('dasbor-detail', compact('data', 'date', 'village', 'month', 'years'));
    }

    public function detail1(Request $request, $district_code, $village_code, $years, $status_id)
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

    private function monthChart($count)
    {
        $now = Carbon::now();
        $startOfYears = $now->startOfYear('Y-m-d')->format('Y-m-d');
        $endOfYears = $now->endOfYear()->format('Y-m-d');
        $period = CarbonPeriod::create($startOfYears, '1 month', $endOfYears);

        $data = [];
        foreach ($period as $dt) {
            $data[] = [
                'text' => $dt->format("F"),
                'number' => $dt->format("m")
            ];
        }

        $data = array_slice($data, 0, $count);

        return $data;
    }
}
