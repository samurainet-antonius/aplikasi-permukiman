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
use Exception;
use Auth;

class EvaluasiController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', Evaluasi::class);

        $search = $request->get('search', '');

        $tahun =  date("Y");

        $auth = Auth::user();
        $user_id = $auth->id;

        $petugas = Petugas::where('users_id',$user_id)->first();

        if($auth->region_code == 1){
            $evaluasi = Evaluasi::where([
                ['tahun',$tahun],
                ['city_code', $petugas->city_code]
            ])
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();
        }
        elseif($auth->region_code == 2){
            $evaluasi = Evaluasi::where([
                ['tahun',$tahun],
                ['city_code', $petugas->city_code],
                ['district_code', $petugas->district_code]
            ])
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();
        }
        elseif($auth->region_code == 3){
            $evaluasi = Evaluasi::where([
                ['tahun',$tahun],
                ['city_code', $petugas->city_code],
                ['district_code', $petugas->district_code],
                ['village_code', $petugas->village_code],
            ])
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();
        }else{
            $evaluasi = Evaluasi::where('tahun',$tahun)
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();
        }

        return view('app.evaluasi.index', compact('evaluasi', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        ini_set('memory_limit', '2048M');
        $this->authorize('create', Evaluasi::class);

        $auth = Auth::user();
        $user_id = $auth->id;

        $petugas = Petugas::where('users_id',$user_id)->first();
        $city_code =  '1207';
        $district_code = '12071';
        if($auth->region_code == 1){
            $city_code = $petugas->city_code;
        }
        elseif($auth->region_code == 2){
            $city_code = $petugas->city_code;
            $district_code = $petugas->district_code;
        }
        elseif($auth->region_code == 3){
            $city_code = $petugas->city_code;
            $district_code = $petugas->district_code;
            $village_code = $petugas->village_code;
        }

        $city = City::where([
            ['province_code', 12],
            ['code',1207]
        ])->orderBy('name', 'ASC')->get();

        $citySelected = $city->toArray();
        $citySelected = $request->has('city') ? $request->city : $city_code;
        $district = Districts::where('city_code', $citySelected)->orderBy('name', 'ASC')->get();

        if($auth->region_code == 2 || $auth->region_code == 3){
            $district = Districts::where([
                ['city_code', $citySelected],
                ['code',$district_code]
            ])->orderBy('name', 'ASC')->get();
        }


        $districtSelected = $district->toArray();
        $districtSelected = $request->has('district') ? $request->district : $district_code;

        if($auth->region_code == 3){
            $village = Village::select('code', 'district_code', 'name', DB::raw("JSON_EXTRACT(meta, '$[0].lat') as latitude, JSON_EXTRACT(meta, '$[0].long') as longitude"))
            ->where(function ($query) use ($request) {
                return $request->district ? $query->from('indonesia_villages')->where('district_code', $districtSelected) : '';
            })
            ->where('code',$village_code)
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->latest()
            ->get();
        }else{
            $village = Village::select('code', 'district_code', 'name', DB::raw("JSON_EXTRACT(meta, '$[0].lat') as latitude, JSON_EXTRACT(meta, '$[0].long') as longitude"))
            ->where(function ($query) use ($request) {
                return $request->district ? $query->from('indonesia_villages')->where('district_code', $districtSelected) : '';
            })
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->latest()
            ->get();
        }

        $kriteria = Kriteria::latest()->get();

        return view('app.evaluasi.create',compact('village', 'city', 'district','kriteria'));
    }

    public function kriteriaCreate(Request $request, $evaluasi_id, $page)
    {
        $kriteria = Kriteria::latest()->get();
        $kriteria = $kriteria->toArray();

        $kriteriaId = $kriteria[$page]['id'];
        $subkriteria = SubKriteria::where('kriteria_id', $kriteriaId)->get();

        foreach($subkriteria as $key => $val) {
            $evaluasiDetail = EvaluasiDetail::where('evaluasi_id', $evaluasi_id)->where('kriteria_id', $kriteriaId)->where('subkriteria_id', $val->id)->first();

            if($evaluasiDetail) {
                $val->evaluasi = $evaluasiDetail->jawaban;
            } else {
                $val->evaluasi = '';
            }
        }

        $data = [
            'next' => $page + 1,
            'prev' => $page - 1,
            'count' => count($kriteria),
            'evaluasi' => $evaluasi_id
        ];

        $kriteria = $kriteria[$page];

        return view('app.evaluasi.form-kriteria', compact('data', 'subkriteria', 'kriteria'));
    }

    public function store(EvaluasiStoreRequest $request)
    {
        $this->authorize('create', Evaluasi::class);

        $validated = $request->validated();

        // $evaluasiDetail = $validated['jawaban'];
        // unset($validated['jawaban']);

        DB::beginTransaction();
        try{
            $evaluasi = Evaluasi::create($validated);

            // foreach ($evaluasiDetail as $kriteriaID => $details) {

            //     $kriteria = Kriteria::find($kriteriaID);

            //     foreach ($details as $subkriteriaID => $value) {

            //         $subkriteria = SubKriteria::find($subkriteriaID);

            //         EvaluasiDetail::insert(array(
            //             'kriteria_id' => $kriteriaID,
            //             'nama_kriteria' => $kriteria->nama,
            //             'subkriteria_id' => $subkriteriaID,
            //             'nama_subkriteria' => $subkriteria->nama,
            //             'jawaban' => $value,
            //             'evaluasi_id'=> $evaluasi->id,
            //             'created_at' => date("Y-m-d H:i:s")
            //         ));
            //     }
            // }
            DB::commit();
            return redirect()
            ->route('evaluasi.create.kriteria', ['evaluasi_id' => $evaluasi, 'page' => 0]);
            // ->withSuccess(__('crud.common.created'));
        }catch(Exception $e){
            DB::rollback();
            return redirect()
            ->route('evaluasi.create')
            ->withErrors(__('crud.common.errors'));
        }

    }

    public function kriteriaStore(EvaluasiKriteriaStoreRequest $request,$evaluasi_id, $page)
    {
        $validated = $request->validated();

        $evaluasiDetail = $validated['jawaban'];
        unset($validated['jawaban']);
        $count = Kriteria::latest()->get()->count();

        DB::beginTransaction();
        try {

            foreach ($evaluasiDetail as $kriteriaID => $details) {

                $kriteria = Kriteria::find($kriteriaID);

                foreach ($details as $subkriteriaID => $value) {

                    $subkriteria = SubKriteria::find($subkriteriaID);
                    $evaluasiDetail = EvaluasiDetail::where('evaluasi_id', $evaluasi_id)->where('kriteria_id', $kriteriaID)->where('subkriteria_id', $subkriteriaID)->first();

                    if($evaluasiDetail) {
                        EvaluasiDetail::find($evaluasiDetail->id)->update(array(
                            'jawaban' => $value,
                            'updated_at' => date("Y-m-d H:i:s")
                        ));
                    } else {
                        EvaluasiDetail::insert(array(
                            'kriteria_id' => $kriteriaID,
                            'nama_kriteria' => $kriteria->nama,
                            'subkriteria_id' => $subkriteriaID,
                            'nama_subkriteria' => $subkriteria->nama,
                            'jawaban' => $value,
                            'evaluasi_id'=> $evaluasi_id,
                            'created_at' => date("Y-m-d H:i:s")
                        ));
                    }

                }
            }
            DB::commit();

            if($count == $page) {
                return redirect()->route('evaluasi.index')->withSuccess(__('crud.common.created'));
            } else {
                return redirect()->route('evaluasi.create.kriteria', ['evaluasi_id' => $evaluasi_id, 'page' => $page]);
            }

            // ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()
                ->route('evaluasi.create')
                ->withErrors(__('crud.common.errors'));
        }
    }

    public function show(Request $request, Evaluasi $evaluasi)
    {
        $this->authorize('view', $evaluasi);

        $kriteria = EvaluasiDetail::where('evaluasi_id',$evaluasi->id)
                    ->groupBy('kriteria_id')
                    ->get();

        $status = StatusKumuh::get();

        $village = Village::select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(indonesia_villages.meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(indonesia_villages.meta, '$[0].long')) as longitude"))
            ->where('code', $evaluasi->village_code)
            ->first();

        return view('app.evaluasi.show', compact('evaluasi','kriteria','status', 'village'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $this->authorize('update', Evaluasi::class);

        $evaluasi = Evaluasi::find($id);

        $auth = Auth::user();
        $user_id = $auth->id;

        $petugas = Petugas::where('users_id',$user_id)->first();
        $city_code =  '1207';
        $district_code = '12071';
        if($auth->region_code == 1){
            $city_code = $petugas->city_code;
        }
        elseif($auth->region_code == 2){
            $city_code = $petugas->city_code;
            $district_code = $petugas->district_code;
        }
        elseif($auth->region_code == 3){
            $city_code = $petugas->city_code;
            $district_code = $petugas->district_code;
            $village_code = $petugas->village_code;
        }

        $city = City::where([
            ['province_code', 12],
            ['code',1207]
        ])->orderBy('name', 'ASC')->get();

        $citySelected = $city->toArray();
        $citySelected = $request->has('city') ? $request->city : $city_code;
        $district = Districts::where('city_code', $citySelected)->orderBy('name', 'ASC')->get();

        if($auth->region_code == 2 || $auth->region_code == 3){
            $district = Districts::where([
                ['city_code', $citySelected],
                ['code',$district_code]
            ])->orderBy('name', 'ASC')->get();
        }


        $districtSelected = $district->toArray();
        $districtSelected = $request->has('district') ? $request->district : $district_code;

        if($auth->region_code == 3){
            $village = Village::select('code', 'district_code', 'name', DB::raw("JSON_EXTRACT(meta, '$[0].lat') as latitude, JSON_EXTRACT(meta, '$[0].long') as longitude"))
            ->where(function ($query) use ($request) {
                return $request->district ? $query->from('indonesia_villages')->where('district_code', $districtSelected) : '';
            })
            ->where('code',$village_code)
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->latest()
            ->get();
        }else{
            $village = Village::select('code', 'district_code', 'name', DB::raw("JSON_EXTRACT(meta, '$[0].lat') as latitude, JSON_EXTRACT(meta, '$[0].long') as longitude"))
            ->where(function ($query) use ($request) {
                return $request->district ? $query->from('indonesia_villages')->where('district_code', $districtSelected) : '';
            })
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->latest()
            ->get();
        }

        $kriteria = Kriteria::latest()->get();

        return view('app.evaluasi.edit',compact('village', 'city', 'district','kriteria','evaluasi'));
    }

    public function update(EvaluasiStoreRequest $request,$id){

        $this->authorize('create', Evaluasi::class);

        $evaluasi = Evaluasi::find($id);

        $validated = $request->validated();

        $evaluasiDetail = $validated['jawaban'];
        unset($validated['jawaban']);

        DB::beginTransaction();
        try{
            $evaluasi = $evaluasi->update($validated);

            EvaluasiDetail::where('evaluasi_id',$id)->delete();

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
                        'evaluasi_id'=> $id,
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
            ->route('evaluasi.update',['id' => $id])
            ->withErrors(__('crud.common.errors'));
        }
    }

    public function destroy($id)
    {
        $this->authorize('delete', Evaluasi::class);

        $evaluasi = Evaluasi::find($id);

        $evaluasi->delete();

        return redirect()
            ->route('evaluasi.index')
            ->withSuccess(__('crud.common.removed'));
    }

    public function changeSatatus(Request $request,$evaluasi_id){

        $status = StatusKumuh::find($request->status);
        $evaluasi = Evaluasi::find($evaluasi_id);

        DB::beginTransaction();
        try{
            $data['status_id']  = $status->id;
            Evaluasi::where('id',$evaluasi_id)->update($data);
            DB::commit();

            return redirect()
            ->route('evaluasi.index')
            ->withSuccess(__('crud.common.created'));
        }catch(Exception $e){
            DB::rollback();
            return redirect()
            ->route('evaluasi',['id' => $evaluasi_id])
            ->withErrors(__('crud.common.errors'));
        }
    }
}
