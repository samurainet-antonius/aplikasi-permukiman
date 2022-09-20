<?php

namespace App\Http\Controllers;

use App\Http\Requests\DistrictsStoreRequest;
use App\Http\Requests\DistrictsUpdateRequest;
use App\Models\City;
use App\Models\Districts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\District;

class DistrictsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Districts::class);

        $search = $request->get('search', '');

        $city = City::where('province_code', 12)->orderBy('name', 'ASC')->get();

        $districts = Districts::select('id', 'code', 'city_code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
            ->where(function ($query) use ($request) {
                return '1207' ? $query->from('indonesia_districts')->where('city_code', '1207') : '1207';
            })
            ->orderBy('city_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.districts.index', compact('districts', 'search', 'city'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Districts::class);

        $code = Districts::orderBy('code', 'DESC')->first()->code;
        $code = $code + 1;

        return view('app.districts.create', compact('code'));
    }

    public function store(DistrictsStoreRequest $request)
    {
        $this->authorize('create', Districts::class);

        $validated = $request->validated();
        $validated['meta'] = json_encode(['lat' => $validated['latitude'], 'long' => $validated['longitude']]);
        unset($validated['latitude']);
        unset($validated['longitude']);

        $districts = Districts::create($validated);

        return redirect()
            ->route('district.index')
            ->withSuccess(__('crud.common.created'));
    }

    public function show(Request $request, Districts $districts)
    {
        $this->authorize('view', $districts);

        return view('app.districts.show', compact('districts'));
    }

    public function edit($id)
    {
        $this->authorize('update', Districts::class);

        $districts = Districts::find($id);
        $code = $districts->code;
        $meta = json_decode($districts->meta);

        return view('app.districts.edit', compact('code', 'meta','districts'));
    }

    public function update(DistrictsUpdateRequest $request, $id)
    {
        $this->authorize('update', Districts::class);

        $validated = $request->validated();
        $validated['meta'] = json_encode(['lat' => $validated['latitude'], 'long' => $validated['longitude']]);
        unset($validated['latitude']);
        unset($validated['longitude']);

        $districts = Districts::find($id);

        $districts->update($validated);

        $districts->syncRoles($request->roles);

        return redirect()
            ->route('district.index')
            ->withSuccess(__('crud.common.saved'));
    }

    public function destroy($id)
    {
        $this->authorize('delete', Districts::class);

        $districts = Districts::find($id);

        $districts->delete();

        return redirect()
            ->route('district.index')
            ->withSuccess(__('crud.common.removed'));
    }

    public function district(Request $request){

        $village = Districts::select('code', 'city_code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
            ->where(function ($query) use ($request) {
                return $request->city ? $query->from('indonesia_cities')->where('city_code', $request->city) : '';
            })
            ->orderBy('city_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->get();

        if(isset($village)){

            foreach ($village as $key => $value) {
                if ($request->select) {
                    if ($request->select == $value->code) {
                        echo "<option value='" . $value->code . "' selected>" . $value->name . "</option>";
                    } else {
                        echo "<option value='" . $value->code . "'>" . $value->name . "</option>";
                    }
                } else {
                    echo "<option value='" . $value->code . "'>" . $value->name . "</option>";
                }
            }
        }else{
            echo "<option>District not found</option>";
        }
    }
}
