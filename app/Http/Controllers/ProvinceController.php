<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProvinceStoreRequest;
use App\Http\Requests\ProvinceUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Province;
use Illuminate\Support\Facades\DB;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', Province::class);

        $search = $request->get('search', '');

        $province = Province::select('id','code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.province.index', compact('province', 'search'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Province::class);

        $code = Province::orderBy('code', 'DESC')->first()->code;
        $code = $code + 1;

        return view('app.province.create', compact('code'));
    }

    public function store(ProvinceStoreRequest $request)
    {
        $this->authorize('create', Province::class);

        $validated = $request->validated();
        $validated['meta'] = json_encode(['lat' => $validated['latitude'], 'long' => $validated['longitude']]);
        unset($validated['latitude']);
        unset($validated['longitude']);

        $province = Province::create($validated);

        return redirect()
            ->route('province.index')
            ->withSuccess(__('crud.common.created'));
    }

    public function show(Request $request, Province $province)
    {
        $this->authorize('view', $province);

        return view('app.province.show', compact('province'));
    }

    public function edit($id)
    {
        $this->authorize('update', Province::class);

        $province = Province::find($id);
        $code = $province->code;
        $meta = json_decode($province->meta);

        return view('app.province.edit', compact('code', 'meta'))->with('province', $province);
    }

    public function update(ProvinceUpdateRequest $request, $id)
    {
        $this->authorize('update', Province::class);

        $validated = $request->validated();
        $validated['meta'] = json_encode(['lat' => $validated['latitude'], 'long' => $validated['longitude']]);
        unset($validated['latitude']);
        unset($validated['longitude']);

        $province = Province::find($id);

        $province->update($validated);

        $province->syncRoles($request->roles);

        return redirect()
            ->route('province.index')
            ->withSuccess(__('crud.common.saved'));
    }

    public function destroy($id)
    {
        $this->authorize('delete', Province::class);

        $province = Province::find($id);

        $province->delete();

        return redirect()
            ->route('province.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
