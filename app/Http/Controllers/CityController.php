<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityStoreRequest;
use App\Http\Requests\CityUpdateRequest;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', City::class);

        $search = $request->get('search', '');

        $city = City::select('id','code', 'province_code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
            ->where(function($query)use($request) {
                $code = $request->code ? $request->code : '12';
                return $query->from('indonesia_cities')->where('province_code',$code);
            })
            ->orderBy('province_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.city.index', compact('city'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', City::class);

        $code = City::orderBy('code', 'DESC')->first()->code;
        $code = $code + 1;

        return view('app.city.create', compact('code'));
    }

    public function store(CityStoreRequest $request)
    {
        $this->authorize('create', City::class);

        $validated = $request->validated();
        $validated['meta'] = json_encode(['lat' => $validated['latitude'], 'long' => $validated['longitude']]);
        unset($validated['latitude']);
        unset($validated['longitude']);

        $city = City::create($validated);

        return redirect()
            ->route('city.index')
            ->withSuccess(__('crud.common.created'));
    }

    public function show(Request $request, City $city)
    {
        $this->authorize('view', $city);

        return view('app.city.show', compact('city'));
    }

    public function edit($id)
    {
        $this->authorize('update', City::class);

        $city = City::find($id);
        $code = $city->code;
        $meta = json_decode($city->meta);

        return view('app.city.edit', compact('code', 'meta'))->with('city', $city);
    }

    public function update(CityUpdateRequest $request, $id)
    {
        $this->authorize('update', City::class);

        $validated = $request->validated();
        $validated['meta'] = json_encode(['lat' => $validated['latitude'], 'long' => $validated['longitude']]);
        unset($validated['latitude']);
        unset($validated['longitude']);

        $city = City::find($id);

        $city->update($validated);

        $city->syncRoles($request->roles);

        return redirect()
            ->route('city.index')
            ->withSuccess(__('crud.common.saved'));
    }

    public function destroy($id)
    {
        $this->authorize('delete', City::class);

        $city = City::find($id);

        $city->delete();

        return redirect()
            ->route('city.index')
            ->withSuccess(__('crud.common.removed'));
    }

    public function city(Request $request)
    {
        $city = City::select('code', 'province_code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
            ->where(function ($query) use ($request) {
                return $request->province ? $query->from('indonesia_cities')->where('province_code', $request->province) : '';
            })
            ->orderBy('province_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->get();

        if (isset($city)) {

            foreach ($city as $key => $value) {
                if($request->select) {
                    if($request->select == $value->code) {
                        echo "<option value='" . $value->code . "' selected>" . $value->name . "</option>";
                    } else {
                        echo "<option value='" . $value->code . "'>" . $value->name . "</option>";
                    }
                } else {
                    echo "<option value='" . $value->code . "'>" . $value->name . "</option>";
                }
            }
        } else {
            echo "<option>City not found</option>";
        }
    }
}
