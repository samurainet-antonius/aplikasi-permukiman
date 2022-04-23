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
use Exception;

class EvaluasiController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', Evaluasi::class);

        $search = $request->get('search', '');

        $tahun = $request->has('tahun') ? $request->tahun : date("Y");

        $evaluasi = Evaluasi::where('tahun',$tahun)
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.evaluasi.index', compact('evaluasi', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Evaluasi::class);

        $city = City::where('province_code', 12)->orderBy('name', 'ASC')->get();

        $citySelected = $city->toArray();
        $citySelected = $request->has('city') ? $request->city : $citySelected[0]['code'];

        $district = Districts::where('city_code', $citySelected)->orderBy('name', 'ASC')->get();

        $districtSelected = $district->toArray();
        $districtSelected = $request->has('district') ? $request->district : $districtSelected[0]['code'];

        $village = Village::select('code', 'district_code', 'name', DB::raw("JSON_VALUE(meta, '$[0].lat') as latitude, JSON_VALUE(meta, '$[0].long') as longitude"))
            ->where(function ($query) use ($request) {
                return $request->district ? $query->from('indonesia_villages')->where('district_code', $districtSelected) : '';
            })
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->latest()
            ->get();

        $kriteria = Kriteria::latest()->get();

        return view('app.evaluasi.create',compact('village', 'city', 'district','kriteria'));
    }

    public function store(EvaluasiStoreRequest $request)
    {
        $this->authorize('create', Evaluasi::class);

        $validated = $request->validated();

        $evaluasiDetail = $validated['jawaban'];
        unset($validated['jawaban']);

        DB::beginTransaction();
        try{
            $evaluasi = Evaluasi::create($validated);

            foreach ($evaluasiDetail as $kriteriaID => $details) {

                $kriteria = Kriteria::find($kriteriaID);

                foreach ($details as $subkriteriaID => $value) {

                    $subkriteria = SubKriteria::find($subkriteriaID);    

                    EvaluasiDetail::insert(array(
                        'kriteria_id' => $kriteriaID,
                        'nama_kriteria' => $kriteria->nama,
                        'subkriteria_id' => $subkriteriaID,
                        'nama_subkriteria' => $subkriteria->nama,
                        'jawaban' => $value,
                        'evaluasi_id'=> $evaluasi->id,
                        'created_at' => date("Y-m-d H:i:s")
                    ));
                }
            }
            DB::commit();
            return redirect()
            ->route('evaluasi.index')
            ->withSuccess(__('crud.common.created'));
        }catch(Exception $e){
            DB::rollback();
            return redirect()
            ->route('evaluasi.create')
            ->withErrors(__('crud.common.errors'));
        }
        
    }
}
