<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Districts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $districts = Districts::select('code', 'city_code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
            ->where(function ($query) use ($request) {
                return $request->city ? $query->from('indonesia_districts')->where('city_code', $request->city) : '';
            })
            ->orderBy('city_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.districts.index', compact('districts', 'search', 'city'));
    }
}
