<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluasiKriteriaStoreRequest;
use App\Models\Petugas;
use App\Models\Evaluasi;
use App\Models\StatusKumuh;
use App\Models\EvaluasiDetail;
use App\Models\City;
use App\Models\Districts;
use App\Models\Village;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EvaluasiStoreRequest;
use App\Http\Requests\EvaluasiUpdateRequest;
use App\Models\EvaluasiFoto;
use App\Models\Log;
use App\Models\PilihanJawaban;
use Exception;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\File;
use App\Exports\EvaluasiExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request, $evaluasi_id){

        $bulan = $this->bulan();

        $evaluasi = Evaluasi::find($evaluasi_id);

        $date = EvaluasiDetail::where('evaluasi_id', $evaluasi_id)->orderBy('created_at', 'DESC')->first();
        $date = date('m', strtotime($date->created_at));
        $cek = $date;
        $date = $request->get('bulan', $date);

        $kriteria = EvaluasiDetail::where('evaluasi_id',$evaluasi_id)
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', $date)
                    ->groupBy('kriteria_id')
                    ->get();

        $evaluasiKriteria = EvaluasiDetail::where('evaluasi_id',$evaluasi_id)
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', $date)
                    ->sum('nilai');

        
        $status = StatusKumuh::where('tahun',date('Y'))->get();
        
        $statusEvaluasi = null;
        $statusID = null;
        foreach ($status as $key => $value) {
            
            if ($value->nilai_min <= $evaluasiKriteria && $value->nilai_max >= $evaluasiKriteria){
                $statusEvaluasi = $value->nama;
                $statusID = $value->id;
            }
        }
        
        $evaluasi->status_id = $statusID;
        $evaluasi->save();

        foreach($kriteria as $val) {

            $val->sub = EvaluasiDetail::where('evaluasi_id', $evaluasi_id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $date)
                ->where('kriteria_id', $val->kriteria_id)
                ->get()->count();

            $val->evaluasi = EvaluasiDetail::where('evaluasi_id', $evaluasi_id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $date)
                ->where('kriteria_id', $val->kriteria_id)
                ->get();

            $val->skor = EvaluasiDetail::where('evaluasi_id', $evaluasi_id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $date)
                ->where('kriteria_id', $val->kriteria_id)
                ->sum('nilai');

            $val->foto = EvaluasiFoto::where('evaluasi_id', $evaluasi_id)
                ->whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $date)
                ->where('kriteria_id', $val->kriteria_id)
                ->get();
        }

        $village = Village::select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(indonesia_villages.meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(indonesia_villages.meta, '$[0].long')) as longitude"))
            ->where('code', $evaluasi->village_code)
            ->first();
        
        $filename = $evaluasi->village->name.".xlsx";

        return Excel::download(new EvaluasiExport($evaluasi,$kriteria,$evaluasiKriteria,$statusEvaluasi), $filename);
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