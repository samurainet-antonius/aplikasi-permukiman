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

class LogController extends Controller
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

        $years = Log::select(DB::raw('CAST(year(created_at) as CHAR) as year'))->groupBy(DB::raw('year(created_at)'))->get()->toArray();
        if ($years) {
            $years = array_column($years, 'year');
        } else {
            $years = date('Y');
            $years = [$years];
        }

        $log = Log::select('keterangan', 'users_id', 'created_at')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date);

        if ($request->district) {
            $log = $log->where('district_code', $request->district);
        }

        if ($request->village && $request->village != "0") {
            $log = $log->where('village_code', $request->village);
        }

        $log = $log->get();

        foreach ($log as $value) {
            $query = Petugas::where('users_id', $value->users_id)->first();
            $value->name = $query->user->name;
            $value->petugas = 'Petugas ' . $query->jabatan . ' ' . ucwords(strtolower($query->village->name));
            $value->tanggal = Carbon::parse($value->created_at)->format('d/m/Y');
            $value->jam = Carbon::parse($value->created_at)->format('H:i');
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

        return response()->json([
            'status' => 200,
            'data' => [
                'log' => $log,
                'tahun' => $years,
                'kecamatan' => $district,
                'desa' => $village,
            ]
        ]);
    }
}
