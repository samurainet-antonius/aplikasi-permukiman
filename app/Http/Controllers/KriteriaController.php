<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\KriteriaStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\KriteriaUpdateRequest;
use App\Models\Kriteria;
use Spatie\Permission\Models\Permission;

class KriteriaController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Kriteria::class);

        $search = $request->get('search', '');

        $kriteria = Kriteria::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.kriteria.index', compact('kriteria', 'search'));
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

        $kriteria = Kriteria::create($validated);

        $kriteria->syncRoles($request->roles);

        return redirect()
            ->route('kriteria.index')
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Kriteria $kriteria)
    {
        $this->authorize('view', $kriteria);

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
