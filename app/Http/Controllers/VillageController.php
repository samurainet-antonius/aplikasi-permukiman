<?php

namespace App\Http\Controllers;

use App\Http\Requests\VillageStoreRequest;
use App\Http\Requests\VillageUpdateRequest;
use App\Models\City;
use App\Models\Districts;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VillageController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Village::class);

        $search = $request->get('search', '');

        $city = City::where('code', 1207)->orderBy('name', 'ASC')->get();
        $district = Districts::where('city_code', '1207')->orderBy('name', 'ASC')->get();

        $village = Village::select('id', 'code', 'district_code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
            ->where(function ($query) use ($request) {
                return $request->district ? $query->from('indonesia_villages')->where('district_code', $request->district) : $query->where('district_code', '120701');
            })
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('app.village.index', compact('village', 'search', 'city', 'district'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Village::class);

        $code = Village::orderBy('code', 'DESC')->first()->code;
        $code = $code + 1;

        return view('app.village.create', compact('code'));
    }

    public function store(VillageStoreRequest $request)
    {
        $this->authorize('create', Village::class);

        $validated = $request->validated();
        $validated['meta'] = json_encode(['lat' => $validated['latitude'], 'long' => $validated['longitude']]);
        unset($validated['latitude']);
        unset($validated['longitude']);

        $village = Village::create($validated);

        return redirect()
            ->route('village.index')
            ->withSuccess(__('crud.common.created'));
    }

    public function show(Request $request, Village $village)
    {
        $this->authorize('view', $village);

        return view('app.village.show', compact('village'));
    }

    public function edit($id)
    {
        $this->authorize('update', Village::class);

        $village = Village::find($id);
        $districts = Districts::where('code', $village->district_code)->first();
        $city = City::where('code', $districts->city_code)->first();
        $code = $village->code;
        $meta = json_decode($village->meta);

        return view('app.village.edit', compact('code', 'meta', 'village', 'districts', 'city'));
    }

    public function update(VillageUpdateRequest $request, $id)
    {
        $this->authorize('update', Village::class);

        $validated = $request->validated();
        $validated['meta'] = json_encode(['lat' => $validated['latitude'], 'long' => $validated['longitude']]);
        unset($validated['latitude']);
        unset($validated['longitude']);

        $village = Village::find($id);

        $village->update($validated);

        $village->syncRoles($request->roles);

        return redirect()
            ->route('village.index')
            ->withSuccess(__('crud.common.saved'));
    }

    public function destroy($id)
    {
        $this->authorize('delete', Village::class);

        $village = Village::find($id);

        $village->delete();

        return redirect()
            ->route('village.index')
            ->withSuccess(__('crud.common.removed'));
    }

    public function village(Request $request){

        $village = Village::select('code', 'district_code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
            ->where(function ($query) use ($request) {
                return $request->district ? $query->from('indonesia_villages')->where('district_code', $request->district) : '';
            })
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->get();

        if(isset($village)){

            foreach ($village as $key => $value) {
                echo "<option value='".$value->code."'>".$value->name."</option>";
            }
        }else{
            echo "<option>Village not found</option>";
        }
    }
}
