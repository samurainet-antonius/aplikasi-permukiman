<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\KriteriaStoreRequest;
use App\Http\Requests\KriteriaUpdateRequest;
use App\Models\Kriteria;
use App\Models\Log;
use App\Models\Petugas;
use App\Models\SubKriteria;
use App\Models\User;
use App\Models\Village;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\District;

class LogController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Log::class);

        $user = Auth::id();
        $users = User::find($user);

        $role = $users->roles[0]->name;
        $petugas = Petugas::where('users_id', $user)->first();

        $village = '';

        switch ($role) {
            case "admin-provinsi":
            case "admin-kabupaten":
            case "bupati":
            case "seksi":
            case "petugas-kabupaten":
            case "kepala-bidang":
            case "kepala-dinas":
                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();

                $selectDistrict = ($request->has('district') && $request->district !== 'semua') ? $request->district : '1';
                $selectVillage = ($request->has('village') && $request->village !== 'semua') ? $request->village : '1';

                if ($request->district_code == 'semua') {
                    $village = '';
                } elseif ($request->district_code) {
                    $village = Village::select('code', 'name')->where('district_code', $request->district_code)->get();
                }

                $req['district'] = 'semua';
                $req['village'] = 'semua';
                break;
            case "admin-kecamatan":
            case "camat":
            case "petugas-kecamatan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('district_code', $district[0]->code)->get();

                $selectDistrict = '0';
                $selectVillage = '1';

                $req['district'] = $petugas->district_code;
                $req['village'] = 'semua';
                break;
            case "admin-kelurahan":
            case "lurah":
            case "petugas-kelurahan":
                $district = District::select('code', 'name')->where('code', $petugas->district_code)->get();
                $village = Village::select('code', 'name')->where('code', $petugas->village_code)->get();

                $selectDistrict = '0';
                $selectVillage = '0';

                $req['district'] = $petugas->district_code;
                $req['village'] = $petugas->village_code;
                break;
            default:
                $district = District::select('code', 'name')->where('city_code', '1207')->orderBy('name', 'ASC')->get();

                $selectDistrict = ($request->has('district') && $request->district !== 'semua') ? $request->district : '1';
                $selectVillage = ($request->has('village') && $request->village !== 'semua') ? $request->village : '1';

                if ($request->district_code == 'semua') {
                    $village = '';
                } elseif ($request->district) {
                    $village = Village::select('code', 'name')->where('district_code', $request->district)->get();
                }

                $req['district'] = 'semua';
                $req['village'] = 'semua';
        }

        if ($request->district_code) {
            $req['district'] = $request->district_code;
        }

        if ($request->village_code) {
            $req['village'] = $request->village_code;
        }

        $log = Log::select(DB::raw('year(created_at) as year'));

        if ($request->district !== "semua") {
            $log = $log->where('district_code', $request->district);
        }

        if ($request->village !== "semua") {
            $log = $log->where('village_code', $request->village);
        }

        if ($request->start_date) {
            $log = $log->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $log = $log->whereDate('created_at', '<=', $request->end_date);
        }

        $log = $log->groupBy(DB::raw('year(created_at)'))->get();
        foreach ($log as $value) {
            $logs = Log::whereYear('created_at', $value->year);

            if ($request->district !== "semua") {
                $logs = $logs->where('district_code', $request->district);
            }

            if ($request->village !== "semua") {
                $logs = $logs->where('village_code', $request->village);
            }

            if ($request->start_date) {
                $logs = $logs->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->end_date) {
                $logs = $logs->whereDate('created_at', '<=', $request->end_date);
            }

            $value->data = $logs->orderBy('created_at', 'DESC')->get();
            foreach ($value->data as $item) {
                $query = Petugas::where('users_id', $item->users_id)->first();
                $item->name = $query->user->name;
                $item->petugas = $query->jabatan . ' ' . ucwords(strtolower($query->village->name));
                $item->tanggal = Carbon::parse($item->created_at)->format('d/m/Y');
            }
        }

        $select = [
            'district' => $selectDistrict,
            'village' => $selectVillage,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ];
        return view('app.log.index', compact('log', 'select', 'req', 'district', 'village',));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Kriteria::class);

        return view('app.kriteria.create');
    }

    /**
     * @param \App\Http\Requests\KriteriaStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(KriteriaStoreRequest $request)
    {
        $this->authorize('create', Kriteria::class);

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $kriteria = Kriteria::create($validated);
            Kriteria::find($kriteria->id)->update([
                'flag_pakai' => 1
            ]);

            $subkriteria = $request->subkriteria;
            $satuan = $request->satuan;

            for ($i = 0; $i < count($subkriteria); $i++) {
                $dataSubkriteria = [
                    'kriteria_id' => $kriteria->id,
                    'nama' => $subkriteria[$i],
                    'satuan' => $satuan[$i]
                ];

                SubKriteria::create($dataSubkriteria);
            }

            DB::commit();
            return redirect()
                ->route('kriteria.index')
                ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()
                ->route('kriteria.create')
                ->withErrors(__('crud.common.errors'));
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Kriteria $kriteria, $id)
    {
        $this->authorize('view', $kriteria);

        $kriteria = Kriteria::find($id);

        $kriteria->subkriteria = SubKriteria::where('kriteria_id', $kriteria->id)->get();

        return view('app.kriteria.show', compact('kriteria'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', Kriteria::class);

        $kriteria = Kriteria::find($id);
        $kriteria->subkriteria = SubKriteria::where('kriteria_id', $id)->get();

        return view('app.kriteria.edit')->with('kriteria', $kriteria);
    }

    /**
     * @param \App\Http\Requests\KriteriaUpdateRequest $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function update(KriteriaUpdateRequest $request, $id)
    {
        $this->authorize('update', Kriteria::class);

        $validated = $request->validated();

        $kriteria = Kriteria::find($id);

        $kriteria->update($validated);

        $kriteria->syncRoles($request->roles);

        $subkriteria = $request->subkriteria;
        $satuan = $request->satuan;
        $subkriteriaId = $request->subkriteria_id;

        SubKriteria::where('kriteria_id', $id)->whereNotIn('id', $subkriteriaId)->delete();

        for ($i = 0; $i < count($subkriteria); $i++) {

            $dataSubkriteria = [
                'kriteria_id' => $kriteria->id,
                'nama' => $subkriteria[$i],
                'satuan' => $satuan[$i]
            ];

            if ($subkriteriaId[$i]) {
                SubKriteria::find($subkriteriaId[$i])->update($dataSubkriteria);
            } else {
                SubKriteria::create($dataSubkriteria);
            }
        }

        return redirect()
            ->route('kriteria.index')
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Kriteria::class);

        $kriteria = Kriteria::find($id);

        $kriteria->delete();

        return redirect()
            ->route('kriteria.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
