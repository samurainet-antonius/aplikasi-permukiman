<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Districts;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeafletController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $district = Districts::where('city_code', 1207)->get();
        return view('app.home', compact('district'));
    }

    public function formVillage(Request $request)
    {
        if($request->district_code) {
            $district = $request->district_code;
        } else {
            $district = Districts::where('city_code', 1207)->first()->code;
        }

        $districts = Districts::select('code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))->where('code', $district)->first();

        $query = Village::select('code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
                ->where('district_code', $district)
                ->get();

        $data = [
            'district' => $districts,
            'data' =>$query
        ];

        return response()->json($data);
    }

    public function village(Request $request)
    {
        $query = Village::select('code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"));

        if($request->district_code) {
            $query = $query->where('district_code', $request->district_code)->get();
        } else {
            $query = $query->where('district_code', 120701)->get();
        }

        // $query = Village::select('code', 'name', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].lat')) as latitude, JSON_UNQUOTE(JSON_EXTRACT(meta, '$[0].long')) as longitude"))
        //     ->where('district_code', 120701)
        //     ->get();

        return response()->json($query);
    }
}
