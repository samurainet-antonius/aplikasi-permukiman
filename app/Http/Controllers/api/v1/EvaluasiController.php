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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
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

        $evaluasi = Evaluasi::select('evaluasi.id', 'evaluasi.village_code', 'evaluasi.district_code', 'lingkungan', 'status_kumuh.nama as status', 'status_kumuh.warna', 'indonesia_districts.name as district', 'indonesia_villages.name as village')
            ->join('status_kumuh', 'evaluasi.status_id', '=', 'status_kumuh.id')
            ->join('indonesia_villages', 'evaluasi.village_code', '=', 'indonesia_villages.code')
            ->join('indonesia_districts', 'evaluasi.district_code', '=', 'indonesia_districts.code')
            ->where('status_kumuh.tahun', date('Y'));

        switch ($role) {
            case "admin-provinsi":
            case "admin-kabupaten":
                $evaluasi = $evaluasi->where('evaluasi.city_code', '1207')->get();
                break;
            case "admin-kecamatan":
                $evaluasi = $evaluasi->where('evaluasi.district_code', $petugas->district_code)->get();
                break;
            case "admin-kelurahan":
                $evaluasi = $evaluasi->where('evaluasi.village_code', $petugas->village_code)->get();
                break;
            default:
                $evaluasi = $evaluasi->where('province_code', 12)->get();
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'evaluasi' => $evaluasi,
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

    public function store(EvaluasiStoreRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $foto = $validated['gambar_delinasi'];
            $fileName = time() . '.' . $foto->getClientOriginalExtension();
            $folder = 'file/evaluasi';
            $foto->move(public_path($folder), $fileName);

            $validated['gambar_delinasi'] = $folder . '/' . $fileName;

            $evaluasi = Evaluasi::create($validated);

            $this->storeKriteria($evaluasi->id);

            DB::commit();
            return response()->json([
                'status' => 200,
                'data' => [
                    'evaluasi_id' => $evaluasi->id,
                    'page' => 0
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'failed', 'message' => $e, 'data' => []], 401);
        }
    }

    public function edit(Request $request)
    {
        $evaluasi = Evaluasi::find($request->evaluasi_id);

        $evaluasi->gambar_delinasi = URL::to('/') . '/' . $evaluasi->gambar_delinasi;
        // $evaluasi->gambar_delinasi = URL::to('/') . '/public/' . $evaluasi->gambar_delinasi;

        return response()->json([
            'status' => 200,
            'data' => $evaluasi
        ]);
    }

    public function update(EvaluasiApiUpdateRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $evaluasi = Evaluasi::find($validated['evaluasi_id']);

            if ($validated['gambar_delinasi'] != 'null') {
                $foto = $validated['gambar_delinasi'];
                $fileName = time() . '.' . $foto->getClientOriginalExtension();
                $folder = 'file/evaluasi';
                $foto->move(public_path($folder), $fileName);

                $validated['gambar_delinasi'] = $folder . '/' . $fileName;
            } else {
                $validated['gambar_delinasi'] = $evaluasi->gambar_delinasi;
            }

            Evaluasi::find($validated['evaluasi_id'])->update($validated);

            DB::commit();
            return response()->json([
                'status' => 200,
                'data' => [
                    'message' => 'Berhasil update data'
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'failed', 'message' => $e, 'data' => []], 401);
        }
    }

    public function filterVillage(Request $request)
    {
        $village = Village::select('code', 'name')->where('district_code', $request->district)->orderBy('name', 'ASC')->get();

        return response()->json([
            'status' => 200,
            'data' => [
                'desa' => $village
            ]
        ]);
    }

    public function createKriteria(Request $request)
    {
        $req = [
            'page' => $request->page,
            'evaluasi_id' => $request->evaluasi_id,
        ];

        $kriteria = Kriteria::select('id', 'nama')->latest()->get();
        $kriteria = $kriteria->toArray();

        $totalKriteria = count($kriteria);
        $first = array_key_first($kriteria);
        $last = array_key_last($kriteria);

        $kriteriaId = $kriteria[$req['page']]['id'];
        $subkriteria = SubKriteria::select('id', 'kriteria_id', 'nama', 'satuan')->where('kriteria_id', $kriteriaId)->orderBy('id', 'ASC')->get();

        foreach ($subkriteria as $key => $val) {
            $evaluasiDetail = EvaluasiDetail::where('evaluasi_id', $req['evaluasi_id'])->where('kriteria_id', $kriteriaId)->where('subkriteria_id', $val->id)->orderBy('created_at', 'DESC')->first();

            if ($evaluasiDetail) {
                $val->evaluasi = $evaluasiDetail->jawaban;
            } else {
                $val->evaluasi = '';
            }
        }

        $evaluasiFoto = EvaluasiFoto::select('id', 'evaluasi_id', 'kriteria_id', 'nama_kriteria', 'foto')->where('evaluasi_id', $req['evaluasi_id'])->where('kriteria_id', $kriteriaId)->get();

        if ($req['page'] == 0) {
            $prev = 0;
        } else {
            $prev = $req['page'] - 1;
        }

        if ($req['page'] == $last) {
            $next = $last;
        } else {
            $next = $req['page'] + 1;
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'prev' => "$prev",
                'page' => $req['page'],
                'next' => "$next",
                'first' => "$first",
                'last' => "$last",
                'foto' => $evaluasiFoto,
                'kriteria' => $kriteria[$req['page']],
                'subkriteria' => $subkriteria
            ]
        ]);
    }

    private function storeKriteria($evaluasiId)
    {
        $kriteria = Kriteria::latest()->get();

        foreach ($kriteria as $value) {
            $subkriteria = SubKriteria::where('kriteria_id', $value->id)
                ->orderBy('id', 'ASC')
                ->get();

            foreach ($subkriteria as $item) {
                EvaluasiDetail::insert(array(
                    'kriteria_id' => $value->id,
                    'nama_kriteria' => $value->nama,
                    'subkriteria_id' => $item->id,
                    'nama_subkriteria' => $item->nama,
                    'jawaban' => '',
                    'skor' => 0,
                    'persen' => '',
                    'nilai' => '',
                    'evaluasi_id' => $evaluasiId,
                    'created_at' => date("Y-m-d H:i:s")
                ));
            }
        }
    }

    public function updateKriteria(Request $request)
    {
        $req = [
            'jawaban' => $request->jawaban,
            'persen' => $request->persen,
            'evaluasi_id' => $request->evaluasi_id,
            'page' => $request->page
        ];

        $kriteria = Kriteria::select('id', 'nama')->latest()->get();
        $kriteria = $kriteria->toArray();

        $first = array_key_first($kriteria);
        $last = array_key_last($kriteria);

        $kriteria = $kriteria[$req['page']];

        $subkriteria = SubKriteria::select('id', 'kriteria_id', 'nama', 'satuan')->where('kriteria_id', $kriteria['id'])->orderBy('id', 'ASC')->get();
        $evaluasiFoto = EvaluasiFoto::where('evaluasi_id', $req['evaluasi_id'])->where('kriteria_id', $kriteria['id'])->get();

        if ($req['page'] == 0) {
            $prev = 0;
        } else {
            $prev = $req['page'] - 1;
        }

        if ($req['page'] == $last) {
            $next = $last;
        } else {
            $next = $req['page'] + 1;
        }

        // DB::beginTransaction();
        // try {

        if ($request->image1) {
            $foto1 = $request->image1;
            $fileName1 = time() . '-1' . '.' . $foto1->getClientOriginalExtension();
            $folder = 'file/evaluasi';
            $foto1->move(public_path($folder), $fileName1);

            EvaluasiFoto::insert([
                'evaluasi_id' => $req['evaluasi_id'],
                'kriteria_id' => $kriteria['id'],
                'nama_kriteria' => $kriteria['nama'],
                'foto' => $folder . '/' . $fileName1,
                'created_at' => date("Y-m-d H:i:s")
            ]);
        }

        if ($request->image2) {
            $foto2 = $request->image2;
            $fileName2 = time() . '-2' . '.' . $foto2->getClientOriginalExtension();
            $folder = 'file/evaluasi';
            $foto2->move(public_path($folder), $fileName2);

            EvaluasiFoto::insert([
                'evaluasi_id' => $req['evaluasi_id'],
                'kriteria_id' => $kriteria['id'],
                'nama_kriteria' => $kriteria['nama'],
                'foto' => $folder . '/' . $fileName2,
                'created_at' => date("Y-m-d H:i:s")
            ]);
        }

        foreach ($subkriteria as $key => $value) {
            $nilai = $this->formula(str_replace("%", "", $req['persen'][$key]));
            $evaluasiDetail = EvaluasiDetail::where('evaluasi_id', $req['evaluasi_id'])
                ->where('kriteria_id', $kriteria['id'])
                ->where('subkriteria_id', $value->id)
                ->orderBy('id', 'ASC')
                ->update([
                    'jawaban' => $req['jawaban'][$key],
                    'skor' => $req['jawaban'][$key],
                    'persen' => $req['persen'][$key],
                    'nilai' => $nilai,
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'prev' => "$prev",
                'page' => $req['page'],
                'next' => "$next",
                'first' => "$first",
                'last' => "$last",
                'evaluasi_id' => $req['evaluasi_id']
            ]
        ]);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return response()->json(['status' => 'failed', 'message' => $e, 'data' => []], 401);
        // }
    }

    private function formula($persen)
    {

        if ($persen >= 76 && $persen <= 100) {
            return 5;
        } elseif ($persen >= 51 && $persen <= 75) {
            return 3;
        } elseif ($persen >= 25 && $persen <= 50) {
            return 1;
        } else {
            return 0;
        }
    }

    public function updateStatus(Request $request)
    {
        $req = [
            'evaluasi_id' => $request->evaluasi_id,
        ];

        $status = StatusKumuh::where('tahun', date('Y'))->get();

        $date = EvaluasiDetail::where('evaluasi_id', $req['evaluasi_id'])->orderBy('created_at', 'DESC')->first();
        $date = date('m', strtotime($date->created_at));
        $date = $request->get('bulan', $date);

        $evaluasiKriteria = EvaluasiDetail::where('evaluasi_id', $req['evaluasi_id'])
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $date)
            ->sum('nilai');

        DB::beginTransaction();
        try {

            $statusID = null;

            foreach ($status as $key => $value) {

                if ($value->nilai_min <= $evaluasiKriteria && $value->nilai_max >= $evaluasiKriteria) {
                    $statusID = $value->id;
                }
            }

            Evaluasi::find($req['evaluasi_id'])->update([
                'status_id' => $statusID
            ]);


            DB::commit();
            return response()->json([
                'status' => 200,
                'data' => [
                    'message' => 'success'
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'failed', 'message' => $e, 'data' => []], 401);
        }
    }

    public function delete(Request $request)
    {
        $evaluasi = Evaluasi::find($request->evaluasi_id);
        $evaluasiFoto = EvaluasiFoto::where('evaluasi_id', $request->evaluasi_id)->get();

        if ($evaluasiFoto) {
            foreach ($evaluasiFoto as $val) {
                File::delete(public_path($val->foto));
                EvaluasiFoto::find($val->id);
            }
        }

        File::delete(public_path($evaluasi->gambar_delinasi));
        $evaluasi->delete();

        return response()->json([
            'status' => 200,
            'data' => [
                'message' => 'Success hapus data'
            ]
        ]);
    }
}
