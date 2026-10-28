<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusKriteriaStoreRequest;
use App\Http\Requests\StatusKriteriaUpdateRequest;
use App\Models\StatusKriteria;
use Illuminate\Http\Request;

class StatusKriteriaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', StatusKriteria::class);

        $search = $request->get('search', '');

        $tahun = $request->get('tahun', date('Y'));

        $statuskriteria = StatusKriteria::where('tahun', $tahun)
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.statuskriteria.index', compact('statuskriteria', 'search', 'tahun'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', StatusKriteria::class);

        return view('app.statuskriteria.create');
    }

    public function store(StatusKriteriaStoreRequest $request)
    {
        $this->authorize('create', StatusKriteria::class);

        $validated = $request->validated();

        $statuskriteria = StatusKriteria::create($validated);

        $statuskriteria->syncRoles($request->roles);

        return redirect()
            ->route('statuskriteria.index')
            ->withSuccess(__('crud.common.created'));
    }

    public function show(Request $request, StatusKriteria $statuskriteria)
    {
        $this->authorize('view', $statuskriteria);

        return view('app.statuskriteria.show', compact('statuskriteria'));
    }

    public function edit($id)
    {
        $this->authorize('update', StatusKriteria::class);

        $statuskriteria = StatusKriteria::find($id);

        return view('app.statuskriteria.edit')->with('statuskriteria', $statuskriteria);
    }

    public function update(StatusKriteriaUpdateRequest $request, $id)
    {
        $this->authorize('update', StatusKriteria::class);

        $validated = $request->validated();

        $statuskriteria = StatusKriteria::find($id);

        $statuskriteria->update($validated);

        $statuskriteria->syncRoles($request->roles);

        return redirect()
            ->route('statuskriteria.index')
            ->withSuccess(__('crud.common.saved'));
    }

    public function destroy($id)
    {
        $this->authorize('delete', StatusKriteria::class);

        $statuskriteria = StatusKriteria::find($id);

        $statuskriteria->delete();

        return redirect()
            ->route('statuskriteria.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
