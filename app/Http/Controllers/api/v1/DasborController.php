<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvaluasiApiUpdateRequest;
use App\Http\Requests\EvaluasiStoreRequest;
use App\Models\Evaluasi;
use App\Models\EvaluasiDetail;
use App\Models\EvaluasiFoto;
use App\Models\Kriteria;
use App\Models\Log;
use App\Models\Petugas;
use App\Models\StatusKumuh;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Village;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Models\District;

class DasborController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function index(Request $request)
    {
        $user = Auth::id();
        $users = User::find($user);

        $role = $users->roles[0]->name;
        $petugas = Petugas::where('users_id', $user)->first();

        $years = Evaluasi::select('tahun')->groupBy('tahun')->get()->toArray();
        if ($years) {
            $years = array_column($years, 'tahun');
        } else {
            $years = date('Y');
            $years = [$years];
        }

        switch ($role) {
            case "admin-provinsi":
            case "admin-kabupaten":

                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();
                $village = Village::select('code', 'name')->where('district_code', $district[0]->code)->orderBy('name', 'ASC')->get();
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

        if ($role != 'admin-kelurahan') {
            $village = $village->toArray();
            array_unshift($village, ['code' => "0", 'name' => 'Semua']);
        }

        $tahun = $request->years ? $request->years : date('Y');
        $range = $request->range ? $request->range : 12;

        $month = $this->monthChart($range);

        $evaluasi = Evaluasi::select('id')->where('tahun', $tahun);

        $text = [];
        if ($request->district) {
            $evaluasi = $evaluasi->where('evaluasi.district_code', $request->district);
            $text['kecamatan'] = ucwords(strtolower(District::where('code', $request->district)->first()->name));
        }

        if ($request->village && $request->village != 0) {
            $evaluasi = $evaluasi->where('evaluasi.village_code', $request->village);
            $text['desa'] = ucwords(strtolower(Village::where('code', $request->village)->first()->name));
        } else {
            $text['desa'] = 'Semua Desa';
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
            $i = 0;
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

                    $series[] = [
                        'y' => $skor,
                    ];

                    $bulan[] = [
                        'bulan' => $mon['text'],
                        'value' => $i
                    ];
                    $i++;
                }
            }

            $data[] = [
                'name' => $val->nama_kriteria,
                'data' => $series
            ];
        }

        $range = [
            [
                'range' => '1 Tahun',
                'value' => '12'
            ],
            [
                'range' => '9 Bulan',
                'value' => '9'
            ],
            [
                'range' => '6 Bulan',
                'value' => '6'
            ],
            [
                'range' => '3 Bulan',
                'value' => '3'
            ],
        ];

        return response()->json([
            'status' => 200,
            'data' => [
                'dasbor' => $data,
                'text' => $text,
                'range' => $range,
                'bulan' => $bulan,
                'tahun' => $years,
                'kecamatan' => $district,
                'desa' => $village,
            ]
        ]);
    }

    private function monthChart($count)
    {
        $now = Carbon::now();
        $startOfYears = $now->startOfYear('Y-m-d')->format('Y-m-d');
        $endOfYears = $now->endOfYear()->format('Y-m-d');
        $period = CarbonPeriod::create($startOfYears, '1 month', $endOfYears)->locale('id');
        $period->settings(['formatFunction' => 'translatedFormat']);

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
