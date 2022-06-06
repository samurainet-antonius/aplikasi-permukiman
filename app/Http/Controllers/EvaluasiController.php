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
use App\Models\EvaluasiFoto;
use App\Models\PilihanJawaban;
use Exception;
use Auth;
use Illuminate\Support\Facades\File;

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

    public function kriteriaCreate($evaluasi_id, $page)
    {
        $result = $this->kriteriaGet($evaluasi_id, $page);

        $data = $result['data'];
        $subkriteria = $result['subkriteria'];
        $kriteria = $result['kriteria'];

        return view('app.evaluasi.form-kriteria', compact('data', 'subkriteria', 'kriteria'));
    }

    public function kriteriaStore(EvaluasiKriteriaStoreRequest $request,$evaluasi_id, $page)
    {
        $validated = $request->validated();

        $count = Kriteria::latest()->get()->count();

        $this->kriteriaPost($validated,$evaluasi_id, $page, $request->file);

        if ($count == $page) {
            $this->countSkor($evaluasi_id);
            return redirect()->route('evaluasi.index')->withSuccess(__('crud.common.created'));
        } else {
            return redirect()->route('evaluasi.create.kriteria', ['evaluasi_id' => $evaluasi_id, 'page' => $page]);
        }
    }

    public function destroyFotoEvaluasiCreate($evaluasi_id, $page, $id)
    {
        $page = $page - 1;
        $this->evaluasiDeleteFoto($id);

        return redirect()->route('evaluasi.create.kriteria', ['evaluasi_id' => $evaluasi_id, 'page' => $page]);
    }

    public function store(EvaluasiStoreRequest $request)
    {
        $this->authorize('create', Evaluasi::class);

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $evaluasi = Evaluasi::create($validated);

            DB::commit();
            return redirect()
                ->route('evaluasi.create.kriteria', ['evaluasi_id' => $evaluasi, 'page' => 0]);
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

        foreach($kriteria as $val) {
            $val->evaluasi = EvaluasiDetail::where('evaluasi_id', $evaluasi->id)
                ->where('kriteria_id', $val->kriteria_id)
                ->get();

            $val->foto = EvaluasiFoto::where('evaluasi_id', $evaluasi->id)
                ->where('kriteria_id', $val->kriteria_id)
                ->get();
        }

        $status = StatusKumuh::get();

        $village = Village::select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(indonesia_villages.meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(indonesia_villages.meta, '$[0].long')) as longitude"))
            ->where('code', $evaluasi->village_code)
            ->first();

        return view('app.evaluasi.show', compact('evaluasi','kriteria','status', 'village'));
    }

    public function edit(Request $request,$id)
    {
        ini_set('memory_limit', '2048M');
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

    public function kriteriaEdit($evaluasi_id, $page)
    {
        $result = $this->kriteriaGet($evaluasi_id, $page);

        $data = $result['data'];
        $subkriteria = $result['subkriteria'];
        $kriteria = $result['kriteria'];

        return view('app.evaluasi.form-kriteria-update', compact('data', 'subkriteria', 'kriteria'));
    }

    public function kriteriaUpdate(EvaluasiKriteriaStoreRequest $request, $evaluasi_id, $page)
    {
        $validated = $request->validated();

        $count = Kriteria::latest()->get()->count();

        $this->kriteriaPost($validated, $evaluasi_id, $page, $request->file);

        if ($count == $page) {
            $this->countSkor($evaluasi_id);
            return redirect()->route('evaluasi.index')->withSuccess(__('crud.common.created'));
        } else {
            return redirect()->route('evaluasi.edit.kriteria', ['evaluasi_id' => $evaluasi_id, 'page' => $page]);
        }
    }

    public function destroyFotoEvaluasiEdit($evaluasi_id, $page, $id)
    {
        $page = $page - 1;
        $this->evaluasiDeleteFoto($id);

        return redirect()->route('evaluasi.edit.kriteria', ['evaluasi_id' => $evaluasi_id, 'page' => $page]);
    }

    public function update(EvaluasiStoreRequest $request,$id){

        $this->authorize('create', Evaluasi::class);

        $evaluasi = Evaluasi::find($id);

        $validated = $request->validated();

        DB::beginTransaction();
        try{
            $evaluasi = $evaluasi->update($validated);

            DB::commit();
            return redirect()
            ->route('evaluasi.edit.kriteria', ['evaluasi_id' => $id, 'page' => 0]);
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

    private function kriteriaGet($evaluasi_id, $page)
    {
        $kriteria = Kriteria::latest()->get();
        $kriteria = $kriteria->toArray();

        $kriteriaId = $kriteria[$page]['id'];
        $subkriteria = SubKriteria::where('kriteria_id', $kriteriaId)->get();
        $evaluasiFoto = EvaluasiFoto::where('evaluasi_id', $evaluasi_id)->where('kriteria_id', $kriteriaId)->get();

        foreach ($subkriteria as $key => $val) {
            $evaluasiDetail = EvaluasiDetail::where('evaluasi_id', $evaluasi_id)->where('kriteria_id', $kriteriaId)->where('subkriteria_id', $val->id)->first();

            if ($evaluasiDetail) {
                $val->evaluasi = $evaluasiDetail->jawaban;
            } else {
                $val->evaluasi = '';
            }
        }

        foreach($subkriteria as $val) {
            $val->pilihan = PilihanJawaban::where('subkriteria_id', $val->id)->get();
        }

        $data = [
            'next' => $page + 1,
            'prev' => $page - 1,
            'count' => count($kriteria),
            'evaluasi' => $evaluasi_id,
            'foto' => $evaluasiFoto
        ];

        $kriteria = $kriteria[$page];

        $result = [
            'kriteria' => $kriteria,
            'data' => $data,
            'subkriteria' => $subkriteria
        ];

        return $result;
    }

    private function kriteriaPost($validated, $evaluasi_id, $page, $foto)
    {
        $evaluasiDetail = $validated['jawaban'];
        unset($validated['jawaban']);

        DB::beginTransaction();
        try {

            foreach ($evaluasiDetail as $kriteriaID => $details) {

                $kriteria = Kriteria::find($kriteriaID);
                $evaluasiFoto = EvaluasiFoto::where('evaluasi_id', $evaluasi_id)->where('kriteria_id', $kriteriaID)->get();

                if ($foto) {

                    $check = count($evaluasiFoto) + count($foto);

                    if ($check <= 2) {
                        foreach ($foto as $keys => $val) {
                            $fileName = time() . '-' . $keys . '.' . $val->getClientOriginalExtension();
                            $folder = 'file/evaluasi';
                            $val->move(public_path($folder), $fileName);

                            EvaluasiFoto::insert([
                                'evaluasi_id' => $evaluasi_id,
                                'kriteria_id' => $kriteriaID,
                                'nama_kriteria' => $kriteria->nama,
                                'foto' => $folder . '/' . $fileName
                            ]);
                        }
                    } else {
                        return redirect()->route('evaluasi.create.kriteria', ['evaluasi_id' => $evaluasi_id, 'page' => $page - 1])
                            ->withErrors('Maksimal unggah 2 file !');
                    }
                } elseif ($evaluasiFoto->isEmpty()) {
                    return redirect()->route('evaluasi.create.kriteria', ['evaluasi_id' => $evaluasi_id, 'page' => $page - 1])
                        ->withErrors('Wajib unggah file minimal 1');
                }

                foreach ($details as $subkriteriaID => $value) {

                    $subkriteria = SubKriteria::find($subkriteriaID);
                    $jawaban = PilihanJawaban::where('subkriteria_id', $subkriteriaID)->where('jawaban', $value)->first();
                    $evaluasiDetail = EvaluasiDetail::where('evaluasi_id', $evaluasi_id)->where('kriteria_id', $kriteriaID)->where('subkriteria_id', $subkriteriaID)->first();

                    if ($evaluasiDetail) {
                        EvaluasiDetail::find($evaluasiDetail->id)->update(array(
                            'jawaban' => $value,
                            'skor' => $jawaban->skor,
                            'updated_at' => date("Y-m-d H:i:s")
                        ));
                    } else {
                        EvaluasiDetail::insert(array(
                            'kriteria_id' => $kriteriaID,
                            'nama_kriteria' => $kriteria->nama,
                            'subkriteria_id' => $subkriteriaID,
                            'nama_subkriteria' => $subkriteria->nama,
                            'jawaban' => $value,
                            'skor' => $jawaban->skor,
                            'evaluasi_id' => $evaluasi_id,
                            'created_at' => date("Y-m-d H:i:s")
                        ));
                    }
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    private function evaluasiDeleteFoto($id)
    {
        $evaluasiFoto = EvaluasiFoto::find($id);
        File::delete(public_path($evaluasiFoto->foto));
        $evaluasiFoto->delete();

        DB::beginTransaction();
        try {
            File::delete(public_path($evaluasiFoto->foto));
            $evaluasiFoto->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    private function countSkor($evaluasi_id)
    {
        $evaluasi = EvaluasiDetail::select('kriteria_id')->where('evaluasi_id', $evaluasi_id)->groupBy('kriteria_id')->get();

        $skorEvaluasi = 0;
        foreach($evaluasi as $value) {

            $evaluasiKriteria = EvaluasiDetail::where('evaluasi_id', $evaluasi_id)->where('kriteria_id', $value->kriteria_id);
            $sum = $evaluasiKriteria->sum('skor');
            $count = $evaluasiKriteria->count();

            $skorKriteria = $sum / $count;

            $skorEvaluasi = $skorEvaluasi + $skorKriteria;
        }

        $statusAll = StatusKumuh::all();
        $max = StatusKumuh::max('nilai_max');

        $status = '';
        foreach($statusAll as $key => $val) {

            if($max == $val->nilai_max) {
                if ($val->nilai_min <= $skorEvaluasi) {
                    $status = $val->id;
                }
            } else {
                if($val->nilai_min <= $skorEvaluasi && $val->nilai_max >= $skorEvaluasi) {
                    $status = $val->id;
                }
            }

        }

        $data['status_id']  = $status;
        Evaluasi::where('id', $evaluasi_id)->update($data);
    }
}
