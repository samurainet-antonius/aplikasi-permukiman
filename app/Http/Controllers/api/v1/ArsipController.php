<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvaluasiApiUpdateRequest;
use App\Http\Requests\EvaluasiStoreRequest;
use App\Models\Evaluasi;
use App\Models\EvaluasiDetail;
use App\Models\EvaluasiFoto;
use App\Models\Kriteria;
use App\Models\Petugas;
use App\Models\StatusKumuh;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Village;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Models\District;

class ArsipController extends Controller
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

        $years = Evaluasi::select('tahun')->where('tahun', '!=', date('Y'))->groupBy('tahun')->get()->toArray();
        if ($years) {
            $years = array_column($years, 'tahun');
        } else {
            $years = date('Y') - 1;
            $years = ["$years"];
        }

        $evaluasi = Evaluasi::select('evaluasi.id', 'evaluasi.tahun', 'evaluasi.village_code', 'evaluasi.district_code', 'lingkungan', 'status_kumuh.nama as status', 'status_kumuh.warna', 'indonesia_districts.name as district', 'indonesia_villages.name as village')
            ->join('status_kumuh', 'evaluasi.status_id', '=', 'status_kumuh.id')
            ->join('indonesia_villages', 'evaluasi.village_code', '=', 'indonesia_villages.code')
            ->join('indonesia_districts', 'evaluasi.district_code', '=', 'indonesia_districts.code')
            // ->where('status_kumuh.tahun', '!=', date('Y'))
            ->where('evaluasi.tahun', '!=', date('Y'));

        if ($request->years) {
            $evaluasi = $evaluasi->where('evaluasi.tahun', $request->years);
        }

        if ($request->district) {
            $evaluasi = $evaluasi->where('evaluasi.district_code', $request->district);
        }

        if ($request->village && $request->village != 0) {
            $evaluasi = $evaluasi->where('evaluasi.village_code', $request->village);
        }

        switch ($role) {
            case "admin-provinsi":
            case "admin-kabupaten":
                $evaluasi = $evaluasi->where('evaluasi.city_code', '1207')->get();

                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();
                $village = Village::select('code', 'name')->where('district_code', $district[0]->code)->orderBy('name', 'ASC')->get();
                break;
            case "admin-kecamatan":
                $evaluasi = $evaluasi->where('evaluasi.district_code', $petugas->district_code)->get();

                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('district_code', $district[0]->code)->orderBy('name', 'ASC')->get();
                break;
            case "admin-kelurahan":
                $evaluasi = $evaluasi->where('evaluasi.village_code', $petugas->village_code)->get();

                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('code', $petugas->village_code)->get();
                break;
            default:
                $evaluasi = $evaluasi->where('province_code', 12)->get();

                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();
                $village = Village::select('code', 'name')->where('district_code', $district[0]->code)->orderBy('name', 'ASC')->get();
        }

        if ($evaluasi) {
            foreach ($evaluasi as $value) {
                $value->warna = $this->adjustBrightness($value->warna, -80);
            }
        }

        if ($role != 'admin-kelurahan') {
            $village = $village->toArray();
            array_unshift($village, ['code' => "0", 'name' => 'Semua']);
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'evaluasi' => $evaluasi,
                'tahun' => $years,
                'kecamatan' => $district,
                'desa' => $village,
            ]
        ]);
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
        for ($i = date("Y"); $i >= "2015"; $i--) {
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

    public function show(Request $request)
    {
        $month = $this->bulan();

        $bulan = [];
        foreach ($month as $key => $val) {
            $bulan[] = [
                'code' => $key,
                'bulan' => $val
            ];
        }

        $date = EvaluasiDetail::where('evaluasi_id', $request->evaluasi_id)->orderBy('created_at', 'DESC')->first();
        $dateMonth = date('m', strtotime($date->created_at));
        $dateYears = date('Y', strtotime($date->created_at));
        // $date = $request->get('bulan', $date);
        $statusPembaruanKriteria = $dateMonth == date('m') ? false : true;

        if ($request->bulan) {
            $dateMonth = $request->bulan;
        }

        $statusEditKriteria = $dateMonth == date('m') ? true : false;


        $kriteria = EvaluasiDetail::select('evaluasi_id', 'kriteria_id', 'nama_kriteria')->where('evaluasi_id', $request->evaluasi_id)
            ->whereYear('created_at', $dateYears)
            ->whereMonth('created_at', $dateMonth)
            ->groupBy('kriteria_id')
            ->get();

        $evaluasiKriteria = EvaluasiDetail::where('evaluasi_id', $request->evaluasi_id)
            ->whereYear('created_at', $dateYears)
            ->whereMonth('created_at', $dateMonth)
            ->sum('nilai');

        $tot = number_format($evaluasiKriteria, 2);
        $evaluasi = Evaluasi::find($request->evaluasi_id);
        $evaluasi->province_code = $evaluasi->province->name;
        $evaluasi->city_code = $evaluasi->city->name;
        $evaluasi->district_code = $evaluasi->district->name;
        $evaluasi->village_code = $evaluasi->village->name;
        $evaluasi->status_evaluasi = $evaluasi->status->nama;
        $evaluasi->total = $tot;
        $evaluasi->lastUpdate = $date->created_at->isoFormat('D MMMM Y');
        unset($evaluasi->province);
        unset($evaluasi->city);
        unset($evaluasi->district);
        unset($evaluasi->village);
        unset($evaluasi->status_id);
        unset($evaluasi->status);
        unset($evaluasi->deleted_at);
        unset($evaluasi->created_at);
        unset($evaluasi->updated_at);

        $status = StatusKumuh::select('tahun', 'nama', 'warna', 'nilai_min', 'nilai_max')->where('tahun', $dateYears)->get();

        foreach ($kriteria as $val) {

            $val->sub = EvaluasiDetail::where('evaluasi_id', $request->evaluasi_id)
                ->whereYear('created_at', $dateYears)
                ->whereMonth('created_at', $dateMonth)
                ->where('kriteria_id', $val->kriteria_id)
                ->get()->count();

            $val->skor = EvaluasiDetail::where('evaluasi_id', $request->evaluasi_id)
                ->whereYear('created_at', $dateYears)
                ->whereMonth('created_at', $dateMonth)
                ->where('kriteria_id', $val->kriteria_id)
                ->sum('nilai');

            $val->color = $this->formulaKriteria(floor($val->skor / $val->sub));
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'status_edit' => $statusEditKriteria,
                'status_pembaruan' => $statusPembaruanKriteria,
                'bulan' => $bulan,
                'select_bulan' => $dateMonth,
                'evaluasi' => $evaluasi,
                'kriteria' => $kriteria,
                'status' => $status
            ]
        ]);
    }

    public function showKriteria(Request $request)
    {
        $detail = EvaluasiDetail::select('nama_subkriteria', 'skor', 'persen')->where('evaluasi_id', $request->evaluasi_id)
            ->whereYear('created_at', $request->tahun)
            ->whereMonth('created_at', $request->bulan)
            ->where('kriteria_id', $request->kriteria_id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $foto = EvaluasiFoto::select('foto')->where('evaluasi_id', $request->evaluasi_id)
            ->whereYear('created_at', $request->tahun)
            ->whereMonth('created_at', $request->bulan)
            ->where('kriteria_id', $request->kriteria_id)
            ->orderBy('created_at', 'DESC')
            ->get();

        foreach ($foto as $val) {
            $val->foto = URL::to('/') . '/public/' . $val->foto;
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'foto' => $foto,
                'detail' => $detail
            ]
        ]);
    }

    private function bulan()
    {
        $now = CarbonPeriod::create('2022-01-01', '2022-12-01')->month()->locale('id');

        $date = [];
        foreach ($now as $key => $val) {
            $key = $key + 1;
            $date[$key] = $val->isoFormat('MMMM');
        }

        return $date;
    }

    private function formulaKriteria($number)
    {

        $status = DB::table('status_kriteria')->get();

        $statusKriteria = '';
        foreach ($status as $key => $value) {
            if ($value->nilai_min <= $number && $value->nilai_max >= $number) {
                $statusKriteria = $value->nama;
            }
        }

        return $statusKriteria;
    }

    private function adjustBrightness($hex, $steps)
    {
        $steps = max(-255, min(255, $steps));

        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0, min(255, $color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }
}
