<?php

namespace App\Http\Controllers;

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

        $city = City::where('province_code', 12)->orderBy('name', 'ASC')->get();
        $district = Districts::where('city_code', $request->city)->orderBy('name', 'ASC')->get();

        $village = Village::select('code', 'district_code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
            ->where(function ($query) use ($request) {
                return $request->district ? $query->from('indonesia_villages')->where('district_code', $request->district) : '';
            })
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.village.index', compact('village', 'search', 'city', 'district'));
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
