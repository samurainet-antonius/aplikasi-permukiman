<?php

namespace App\Http\Controllers;

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

        $village = Village::select('code', 'district_code', 'name', DB::raw("JSON_VALUE(meta, '$[0].lat') as latitude, JSON_VALUE(meta, '$[0].long') as longitude"))
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.village.index', compact('village', 'search'));
    }
}
