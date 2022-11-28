<?php

namespace App\Http\Controllers;

use App\Models\Evaluasi;
use App\Models\EvaluasiDetail;
use App\Models\City;
use App\Models\Districts;
use App\Models\Village;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EvaluasiStoreRequest;
use Exception;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', Evaluasi::class);

        $search = $request->get('search', '');

        $tahun = $request->has('tahun') ? $request->tahun : date("Y") - 1;

        $evaluasi = Evaluasi::where('tahun', $tahun)
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('app.arsip.index', compact('evaluasi', 'search'));
    }

    public function show(Request $request, Evaluasi $evaluasi)
    {
        $this->authorize('view', $evaluasi);

        $kriteria = EvaluasiDetail::where('evaluasi_id', $evaluasi->id)
            ->groupBy('kriteria_id')
            ->get();

        return view('app.arsip.show', compact('evaluasi', 'kriteria'));
    }

    public function destroy($id)
    {
        $this->authorize('delete', Evaluasi::class);

        $evaluasi = Evaluasi::find($id);

        $evaluasi->delete();

        return redirect()
            ->route('arsip.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
