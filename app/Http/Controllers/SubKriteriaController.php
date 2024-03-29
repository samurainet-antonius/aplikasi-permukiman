<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubKriteriaStoreRequest;
use App\Http\Requests\SubKriteriaUpdateRequest;
use App\Models\Kriteria;
use App\Models\PilihanJawaban;
use App\Models\SubKriteria;
use Exception;
use Illuminate\Support\Facades\DB;

class SubKriteriaController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', SubKriteria::class);

        $search = $request->get('search', '');


        $kriteria = Kriteria::get();
        $kriteriaOne = Kriteria::first();

        $kriteriaSelected = $request->get('kriteria', $kriteriaOne->id);

        $subkriteria = SubKriteria::where('kriteria_id',$kriteriaSelected)
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.subkriteria.index', compact('subkriteria', 'search','kriteria','kriteriaSelected'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', SubKriteria::class);

        $kriteria = Kriteria::all();

        return view('app.subkriteria.create')->with(compact('kriteria'));
    }

    /**
     * @param \App\Http\Requests\SubKriteriaStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubKriteriaStoreRequest $request)
    {
        // $this->authorize('create', SubKriteria::class);

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $subkriteria = SubKriteria::create($validated);
            Kriteria::find($validated['kriteria_id'])->update([
                'flag_pakai' => 1
            ]);

            $jawaban = $request->jawaban;
            $skor = $request->skor;

            for($i = 0; $i < count($jawaban); $i++) {
                $pilihanJawaban = [
                    'subkriteria_id' => $subkriteria->id,
                    'jawaban' => $jawaban[$i],
                    'skor' => $skor[$i]
                ];

                PilihanJawaban::create($pilihanJawaban);
            }


            DB::commit();
            return redirect()
                ->route('subkriteria.index')
                ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()
                ->route('subkriteria.create')
                ->withErrors(__('crud.common.errors'));
        }

        $subkriteria->syncRoles($request->roles);


    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\SubKriteria $subkriteria
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, SubKriteria $subkriteria)
    {
        $this->authorize('view', $subkriteria);

        return view('app.subkriteria.show', compact('subkriteria'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\SubKriteria $subkriteria
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', SubKriteria::class);

        $subkriteria = SubKriteria::find($id);
        $kriteria = Kriteria::all();

        $subkriteria->pilihan = PilihanJawaban::where('subkriteria_id', $id)->get();
        $subkriteria->count = count($subkriteria->pilihan);

        return view('app.subkriteria.edit')->with('subkriteria', $subkriteria)->with(compact('kriteria'));
    }

    /**
     * @param \App\Http\Requests\SubKriteriaUpdateRequest $request
     * @param \App\Models\SubKriteria $subkriteria
     * @return \Illuminate\Http\Response
     */
    public function update(SubKriteriaUpdateRequest $request, $id)
    {
        $this->authorize('update', SubKriteria::class);

        $validated = $request->validated();

        $subkriteria = SubKriteria::find($id);

        if($validated['kriteria_id'] != $subkriteria->kriteria_id) {
            $kriteria = SubKriteria::where('kriteria_id', $validated['kriteria_id'])->where('id', '!=', $id)->first();
            if (!$kriteria) {
                Kriteria::find($validated['kriteria_id'])->update([
                    'flag_pakai' => 1
                ]);
            }

            $kriteria = SubKriteria::where('kriteria_id', $subkriteria->kriteria_id)->where('id', '!=', $id)->first();
            if (!$kriteria) {
                Kriteria::find($subkriteria->kriteria_id)->update([
                    'flag_pakai' => 0
                ]);
            }
        }

        PilihanJawaban::where('subkriteria_id', $subkriteria->id)->delete();

        $jawaban = $request->jawaban;
        $skor = $request->skor;

        for ($i = 0; $i < count($jawaban); $i++) {
            $pilihanJawaban = [
                'subkriteria_id' => $subkriteria->id,
                'jawaban' => $jawaban[$i],
                'skor' => $skor[$i]
            ];

            PilihanJawaban::create($pilihanJawaban);
        }

        $subkriteria->update($validated);

        $subkriteria->syncRoles($request->roles);

        return redirect()
            ->route('subkriteria.index')
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\SubKriteria $subkriteria
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', SubKriteria::class);

        $subkriteria = SubKriteria::find($id);

        $kriteria = SubKriteria::where('kriteria_id', $subkriteria->kriteria_id)->where('id', '!=', $id)->first();
        if(!$kriteria) {
            Kriteria::find($subkriteria->kriteria_id)->update([
                'flag_pakai' => 0
            ]);
        }

        $subkriteria->delete();

        return redirect()
            ->route('subkriteria.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
