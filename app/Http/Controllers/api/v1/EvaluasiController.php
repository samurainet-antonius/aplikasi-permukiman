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

class EvaluasiController extends Controller
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

        $evaluasi = Evaluasi::select('evaluasi.id', 'evaluasi.village_code', 'evaluasi.district_code', 'lingkungan', 'status_kumuh.nama as status', 'status_kumuh.warna', 'indonesia_districts.name as district', 'indonesia_villages.name as village')
            ->join('status_kumuh', 'evaluasi.status_id', '=', 'status_kumuh.id')
            ->join('indonesia_villages', 'evaluasi.village_code', '=', 'indonesia_villages.code')
            ->join('indonesia_districts', 'evaluasi.district_code', '=', 'indonesia_districts.code')
            ->where('status_kumuh.tahun', date('Y'))
            ->whereYear('evaluasi.created_at', date('Y'));

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

        $evaluasi->gambar_delinasi = URL::to('/') . '/public/' . $evaluasi->gambar_delinasi;
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

        $dateEval = EvaluasiDetail::where('evaluasi_id', $request->evaluasi_id)->orderBy('created_at', 'DESC')->first();
        $date = date('m', strtotime($dateEval->created_at));
        // $date = $request->get('bulan', $date);
        $statusPembaruanKriteria = $date == date('m') ? false : true;

        if ($request->bulan) {
            $date = $request->bulan;
        }

        $statusEditKriteria = $date == date('m') ? true : false;


        $kriteria = EvaluasiDetail::select('evaluasi_id', 'kriteria_id', 'nama_kriteria')->where('evaluasi_id', $request->evaluasi_id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $date)
            ->groupBy('kriteria_id')
            ->get();

        $evaluasiKriteria = EvaluasiDetail::where('evaluasi_id', $request->evaluasi_id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $date)
            ->sum('skor');
        $tot = number_format($evaluasiKriteria, 2);
        $evaluasi = Evaluasi::find($request->evaluasi_id);
        $evaluasi->province_code = $evaluasi->province->name;
        $evaluasi->city_code = $evaluasi->city->name;
        $evaluasi->district_code = $evaluasi->district->name;
        $evaluasi->village_code = $evaluasi->village->name;
        $evaluasi->status_evaluasi = $evaluasi->status->nama;
        $evaluasi->total = $tot;
        $evaluasi->lastUpdate = $dateEval->created_at->isoFormat('D MMMM Y');
        unset($evaluasi->province);
        unset($evaluasi->city);
        unset($evaluasi->district);
        unset($evaluasi->village);
        unset($evaluasi->status_id);
        unset($evaluasi->status);
        unset($evaluasi->deleted_at);
        unset($evaluasi->created_at);
        unset($evaluasi->updated_at);

        $status = StatusKumuh::select('tahun', 'nama', 'warna', 'nilai_min', 'nilai_max')->where('tahun', date('Y'))->get();

        foreach ($kriteria as $val) {

            $val->sub = EvaluasiDetail::where('evaluasi_id', $request->evaluasi_id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $date)
                ->where('kriteria_id', $val->kriteria_id)
                ->get()->count();

            $val->skor = EvaluasiDetail::where('evaluasi_id', $request->evaluasi_id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $date)
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
                'select_bulan' => $date,
                'evaluasi' => $evaluasi,
                'kriteria' => $kriteria,
                'status' => $status
            ]
        ]);
    }

    public function showKriteria(Request $request)
    {
        $detail = EvaluasiDetail::select('nama_subkriteria', 'skor', 'persen')->where('evaluasi_id', $request->evaluasi_id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $request->bulan)
            ->where('kriteria_id', $request->kriteria_id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $foto = EvaluasiFoto::select('foto')->where('evaluasi_id', $request->evaluasi_id)
            ->whereYear('created_at', date('Y'))
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

    public function editDetailKriteria(Request $request)
    {
        $kriteria = Kriteria::find($request->kriteria_id);
        $subkriteria = SubKriteria::select('id', 'kriteria_id', 'nama', 'satuan')->where('kriteria_id', $request->kriteria_id)->orderBy('id', 'ASC')->get();

        $foto = EvaluasiFoto::select('foto')->where('evaluasi_id', $request->evaluasi_id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->where('kriteria_id', $request->kriteria_id)
            ->orderBy('created_at', 'DESC')
            ->get();

        foreach ($foto as $value) {
            $value->foto = URL::to('/') . '/public/' . $value->foto;
        }

        foreach ($subkriteria as $value) {
            $detail = EvaluasiDetail::select('nama_subkriteria', 'skor', 'persen')->where('evaluasi_id', $request->evaluasi_id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
                ->where('kriteria_id', $request->kriteria_id)
                ->where('subkriteria_id', $value->id)
                ->orderBy('created_at', 'DESC')
                ->first();

            $value->skor = $detail->skor;
            $value->persen = $detail->persen;
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'kriteria' => $kriteria,
                'foto' => $foto,
                'subkriteria' => $subkriteria
            ]
        ]);
    }

    public function updateDetailKriteria(Request $request)
    {
        $req = [
            'jawaban' => $request->jawaban,
            'persen' => $request->persen,
            'evaluasi_id' => $request->evaluasi_id,
            'kriteria_id' => $request->kriteria_id,
        ];

        $kriteria = Kriteria::find($req['kriteria_id']);

        $subkriteria = SubKriteria::select('id', 'kriteria_id', 'nama', 'satuan')->where('kriteria_id', $req['kriteria_id'])->orderBy('id', 'ASC')->get();
        $evaluasiFoto = EvaluasiFoto::where('evaluasi_id', $req['evaluasi_id'])
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->where('kriteria_id', $req['kriteria_id'])
            ->get();

        // DB::beginTransaction();
        // try {

        if ($request->image1) {
            $foto1 = $request->image1;
            $fileName1 = time() . '-1' . '.' . $foto1->getClientOriginalExtension();
            $folder = 'file/evaluasi';
            $foto1->move(public_path($folder), $fileName1);

            $evaluasiImage = $evaluasiFoto[0];
            File::delete(public_path($evaluasiImage->foto));

            EvaluasiFoto::find($evaluasiImage->id)->update([
                'foto' => $folder . '/' . $fileName1,
                'created_at' => date("Y-m-d H:i:s")
            ]);
        }

        if ($request->image2) {
            $foto2 = $request->image2;
            $fileName2 = time() . '-2' . '.' . $foto2->getClientOriginalExtension();
            $folder = 'file/evaluasi';
            $foto2->move(public_path($folder), $fileName2);

            if ($evaluasiFoto->count() >= 2) {
                $evaluasiImage = $evaluasiFoto[1];
                File::delete(public_path($evaluasiImage->foto));

                EvaluasiFoto::find($evaluasiImage->id)->update([
                    'foto' => $folder . '/' . $fileName2,
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            } else {
                EvaluasiFoto::insert([
                    'evaluasi_id' => $req['evaluasi_id'],
                    'kriteria_id' => $req['kriteria_id'],
                    'nama_kriteria' => $kriteria['nama'],
                    'foto' => $folder . '/' . $fileName2,
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
        }

        foreach ($subkriteria as $key => $value) {
            $nilai = $this->formula(str_replace("%", "", $req['persen'][$key]));
            $evaluasiDetail = EvaluasiDetail::where('evaluasi_id', $req['evaluasi_id'])
                ->where('kriteria_id', $req['kriteria_id'])
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
                'evaluasi_id' => $req['evaluasi_id']
            ]
        ]);
    }

    public function createPembaruan(Request $request)
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
            $evaluasiDetail = EvaluasiDetail::where('evaluasi_id', $req['evaluasi_id'])
                ->whereMonth('created_at', '!=', date('m'))
                ->where('kriteria_id', $kriteriaId)
                ->where('subkriteria_id', $val->id)
                ->orderBy('created_at', 'DESC')
                ->first();

            $val->skor = $evaluasiDetail->skor;
            $val->persen = $evaluasiDetail->persen;

            if ($evaluasiDetail) {
                $val->evaluasi = $evaluasiDetail->jawaban;
            } else {
                $val->evaluasi = '';
            }
        }

        $evaluasiFoto = EvaluasiFoto::select(DB::raw('MONTH(created_at) as bulan'))
            ->whereMonth('created_at', '!=', date('m'))
            ->where('evaluasi_id', $req['evaluasi_id'])
            ->where('kriteria_id', $kriteriaId)
            ->first();

        $evaluasiFoto = EvaluasiFoto::select('id', 'evaluasi_id', 'kriteria_id', 'nama_kriteria', 'foto')
            ->whereMonth('created_at', '!=', date('m'))
            ->whereMonth('created_at', $evaluasiFoto->bulan)
            ->where('evaluasi_id', $req['evaluasi_id'])
            ->where('kriteria_id', $kriteriaId)
            ->get();

        foreach ($evaluasiFoto as $value) {
            $value->foto = URL::to('/') . '/public/' . $value->foto;
        }

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

    public function storePembaruan(Request $request)
    {
        $this->storeKriteria($request->evaluasi_id);

        return response()->json([
            'status' => 200,
            'data' => [
                'message' => 'Success'
            ]
        ]);
    }

    public function updatePembaruan(Request $request)
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

        $subkriteria = SubKriteria::select('id', 'kriteria_id', 'nama', 'satuan')
            ->where('kriteria_id', $kriteria['id'])
            ->orderBy('id', 'ASC')
            ->get();

        $evaluasiFoto = EvaluasiFoto::where('evaluasi_id', $req['evaluasi_id'])
            ->where('kriteria_id', $kriteria['id'])
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->get();

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
        } else {
            $folder = 'file/pembaruan';
            $fileName = explode('/', $evaluasiFoto[0]->foto);

            File::copy(public_path($evaluasiFoto[0]->foto), public_path($folder . '/' . $fileName[2]));

            EvaluasiFoto::insert([
                'evaluasi_id' => $evaluasiFoto[0]->evaluasi_id,
                'kriteria_id' => $evaluasiFoto[0]->kriteria_id,
                'nama_kriteria' => $evaluasiFoto[0]->nama_kriteria,
                'foto' => $folder . '/' . $fileName[2],
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
        } elseif (count($evaluasiFoto) >= 2) {
            $folder = 'file/pembaruan';
            $fileName = explode('/', $evaluasiFoto[1]->foto);

            File::copy(public_path($evaluasiFoto[1]->foto), public_path($folder . '/' . $fileName[2]));

            EvaluasiFoto::insert([
                'evaluasi_id' => $evaluasiFoto[1]->evaluasi_id,
                'kriteria_id' => $evaluasiFoto[1]->kriteria_id,
                'nama_kriteria' => $evaluasiFoto[1]->nama_kriteria,
                'foto' => $folder . '/' . $fileName[2],
                'created_at' => date("Y-m-d H:i:s")
            ]);
        }

        foreach ($subkriteria as $key => $value) {
            $nilai = $this->formula(str_replace("%", "", $req['persen'][$key]));
            $evaluasiDetail = EvaluasiDetail::where('evaluasi_id', $req['evaluasi_id'])
                ->where('kriteria_id', $kriteria['id'])
                ->where('subkriteria_id', $value->id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
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
