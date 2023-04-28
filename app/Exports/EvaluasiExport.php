<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EvaluasiExport implements FromView
{
    protected $evaluasi;
    protected $kriteria;
    protected $evaluasiKriteria;
    protected $statusEvaluasi;

    function __construct($evaluasi,$kriteria,$evaluasiKriteria,$statusEvaluasi) {
        $this->evaluasi = $evaluasi;
        $this->kriteria = $kriteria;
        $this->evaluasiKriteria = $evaluasiKriteria;
        $this->statusEvaluasi = $statusEvaluasi;
    }


    public function view() : View
    {
        return view('app.report.export', [
            'evaluasi' => $this->evaluasi,
            'kriteria' => $this->kriteria,
            'evaluasiKriteria' => $this->evaluasiKriteria,
            'statusEvaluasi' => $this->statusEvaluasi,
        ]);
    }
}
