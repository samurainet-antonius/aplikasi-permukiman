<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StatusKumuhStoreRequest;
use App\Http\Requests\StatusKumuhUpdateRequest;
use App\Models\StatusKumuh;

class StatusKumuhController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', StatusKumuh::class);

        $search = $request->get('search', '');

        $statuskumuh = StatusKumuh::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.statuskumuh.index', compact('statuskumuh', 'search'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', StatusKumuh::class);

        return view('app.statuskumuh.create');
    }

    public function store(StatusKumuhStoreRequest $request)
    {
        $this->authorize('create', StatusKumuh::class);

        $validated = $request->validated();

        $statuskumuh = StatusKumuh::create($validated);

        $statuskumuh->syncRoles($request->roles);

        return redirect()
            ->route('statuskumuh.index')
            ->withSuccess(__('crud.common.created'));
    }

    public function show(Request $request, StatusKumuh $statuskumuh)
    {
        $this->authorize('view', $statuskumuh);

        return view('app.statuskumuh.show', compact('statuskumuh'));
    }

    public function edit($id)
    {
        $this->authorize('update', StatusKumuh::class);

        $statuskumuh = StatusKumuh::find($id);

        return view('app.statuskumuh.edit')->with('statuskumuh', $statuskumuh);
    }

    public function update(StatusKumuhUpdateRequest $request, $id)
    {
        $this->authorize('update', StatusKumuh::class);

        $validated = $request->validated();

        $statuskumuh = StatusKumuh::find($id);

        $statuskumuh->update($validated);

        $statuskumuh->syncRoles($request->roles);

        return redirect()
            ->route('statuskumuh.index')
            ->withSuccess(__('crud.common.saved'));
    }

    public function destroy($id)
    {
        $this->authorize('delete', StatusKumuh::class);

        $statuskumuh = StatusKumuh::find($id);

        $statuskumuh->delete();

        return redirect()
            ->route('statuskumuh.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
