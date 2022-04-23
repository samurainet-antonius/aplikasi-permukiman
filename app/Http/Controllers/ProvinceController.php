<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use Illuminate\Support\Facades\DB;

class ProvinceController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Province::class);

        $search = $request->get('search', '');

        $province = Province::select('code', 'name', DB::raw("JSON_EXTRACT(meta, '$[0].lat') as latitude, JSON_EXTRACT(meta, '$[0].long') as longitude"))->search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        // $province = new IndonesiaService;
        // $province = $province->paginateProvinces(5);

        return view('app.province.index', compact('province', 'search'));
    }
}
