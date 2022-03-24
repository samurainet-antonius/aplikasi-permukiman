<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', City::class);

        $search = $request->get('search', '');

        $city = City::select('code', 'province_code', 'name', DB::raw("JSON_VALUE(meta, '$[0].lat') as latitude, JSON_VALUE(meta, '$[0].long') as longitude"))
            ->orderBy('province_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.city.index', compact('city', 'search'));
    }
}
