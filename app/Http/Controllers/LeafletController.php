<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Districts;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Evaluasi;
use App\Models\StatusKumuh;
use App\Models\EvaluasiDetail;
use App\Models\EvaluasiFoto;
use Carbon\CarbonPeriod;

class LeafletController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $district = Districts::where('city_code', 1207)->get();
        return view('app.home', compact('district'));
    }

    public function formVillage(Request $request)
    {
        if ($request->district_code) {
            $district = $request->district_code;
        } else {
            $district = Districts::where('city_code', 1207)->first()->code;
        }

        $districts = Districts::select('code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))->where('code', $district)->first();

        $query = Village::select('code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
            ->where('district_code', $district)
            ->get();

        $data = [
            'district' => $districts,
            'data' => $query
        ];

        return response()->json($data);
    }

    public function selectVillage(Request $request)
    {
        $village = Village::select('code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
            ->where('code', $request->code)
            ->first();

        return response()->json($village);
    }

    public function village(Request $request)
    {
        $query = Village::select(
            'indonesia_villages.code',
            'indonesia_villages.name',
            'indonesia_districts.name as kecamatan',
            'status_kumuh.nama as status',
            'warna',
            'icon',
            'evaluasi.gambar_delinasi as gambar',
            'evaluasi.id as id',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(indonesia_villages.meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(indonesia_villages.meta, '$[0].long')) as longitude")
        );

        // if($request->district_code) {
        //     $query = $query->leftJoin('indonesia_districts', 'indonesia_districts.code', '=', 'indonesia_villages.district_code')
        //         ->where('district_code', $request->district_code)
        //         ->get();
        // } else {
        //     $query = $query->leftJoin('indonesia_districts', 'indonesia_districts.code', '=', 'indonesia_villages.district_code')
        //         ->where('district_code', 120701)
        //         ->get();
        // }


        $district = Districts::select('code')->where('city_code', 1207)->get();

        $query = $query->join('evaluasi', 'evaluasi.village_code', '=', 'indonesia_villages.code');
        $query = $query->join('status_kumuh', 'status_kumuh.id', '=', 'evaluasi.status_id');

        $query = $query->leftJoin('indonesia_districts', 'indonesia_districts.code', '=', 'indonesia_villages.district_code')
            ->where('evaluasi.deleted_at', null)
            ->whereIn('indonesia_villages.district_code', $district->toArray())
            ->get();

        // $query = Village::select('code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
        //     ->where('district_code', 120701)
        //     ->get();

        return response()->json($query);
    }

    public function detail(Request $request, $id)
    {

        $bulan = $this->bulan();

        $evaluasi = Evaluasi::find($id);

        $date = EvaluasiDetail::where('evaluasi_id', $id)->orderBy('created_at', 'DESC')->first();
        $date = date('m', strtotime($date->created_at));
        $cek = $date;
        $date = $request->get('bulan', $date);

        $kriteria = EvaluasiDetail::where('evaluasi_id', $id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $date)
            ->groupBy('kriteria_id')
            ->get();

        $evaluasiKriteria = EvaluasiDetail::where('evaluasi_id', $id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $date)
            ->sum('nilai');


        $status = StatusKumuh::where('tahun', date('Y'))->get();

        $statusEvaluasi = '';
        $statusID = '';
        foreach ($status as $key => $value) {

            if ($value->nilai_min <= $evaluasiKriteria && $value->nilai_max >= $evaluasiKriteria) {
                $statusEvaluasi = $value->nama;
                $statusID = $value->id;
            }
        }

        $evaluasi->status_id = $statusID;
        $evaluasi->save();

        foreach ($kriteria as $val) {

            $val->sub = EvaluasiDetail::where('evaluasi_id', $id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $date)
                ->where('kriteria_id', $val->kriteria_id)
                ->get()->count();

            $val->evaluasi = EvaluasiDetail::select('evaluasi_detail.*', 'subkriteria.satuan')
                ->join('subkriteria', 'evaluasi_detail.subkriteria_id', '=', 'subkriteria.id')
                ->where('evaluasi_id', $id)
                ->whereYear('evaluasi_detail.created_at', date('Y'))
                ->whereMonth('evaluasi_detail.created_at', $date)
                ->where('evaluasi_detail.kriteria_id', $val->kriteria_id)
                ->get();

            $val->skor = EvaluasiDetail::where('evaluasi_id', $id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $date)
                ->where('kriteria_id', $val->kriteria_id)
                ->sum('nilai');

            $val->foto = EvaluasiFoto::where('evaluasi_id', $id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $date)
                ->where('kriteria_id', $val->kriteria_id)
                ->get();
        }

        $village = Village::select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(indonesia_villages.meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(indonesia_villages.meta, '$[0].long')) as longitude"))
            ->where('code', $evaluasi->village_code)
            ->first();

        // dd($kriteria[0]);

        return view('app.detail', compact('status', 'evaluasiKriteria', 'statusEvaluasi', 'evaluasi', 'kriteria', 'status', 'village', 'bulan', 'date', 'cek'));
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
}
