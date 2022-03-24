<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubKriteriaStoreRequest;
use App\Http\Requests\SubKriteriaUpdateRequest;
use App\Models\Kriteria;
use App\Models\SubKriteria;

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

        $subkriteria = SubKriteria::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.subkriteria.index', compact('subkriteria', 'search'));
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
        $this->authorize('create', SubKriteria::class);

        $validated = $request->validated();

        $subkriteria = SubKriteria::create($validated);
        Kriteria::find($validated['kriteria_id'])->update([
            'flag_pakai' => 1
        ]);

        $subkriteria->syncRoles($request->roles);

        return redirect()
            ->route('subkriteria.index')
            ->withSuccess(__('crud.common.created'));
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
