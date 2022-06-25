<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\KriteriaStoreRequest;
use App\Http\Requests\KriteriaUpdateRequest;
use App\Models\Kriteria;
use App\Models\Log;
use App\Models\Petugas;
use App\Models\SubKriteria;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Log::class);

        $log = Log::all();
        $log = Log::select(DB::raw('year(created_at) as year'))
            ->groupBy(DB::raw('year(created_at)'))
            ->get();

        foreach($log as $value) {
            $value->data = Log::whereYear('created_at', $value->year)
                ->orderBy('created_at' , 'DESC')
                ->get();

            foreach($value->data as $item) {
                $query = Petugas::where('users_id', $item->users_id)->first();
                $item->name = $query->user->name;
                $item->petugas = $query->jabatan.' '. ucwords(strtolower($query->village->name));
                $item->tanggal = Carbon::parse($item->created_at)->format('d/m/Y');
            }
        }

        return view('app.log.index', compact('log'));
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

            if($subkriteriaId[$i]) {
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
