<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $evaluasi->province->name }}</title>
    <link href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container">
        <h3 class="text-center mb-4">TINGKAT KEKUMUHAN AWAL</h3>
        <hr/>
        <div class="row mb-5">
            <div class="col-md-6">
                <table>
                    <thead>
                        <tr>
                            <th>Provinsi</th>
                            <td>: {{ $evaluasi->province->name }}</td>
                        </tr>
                        <tr>
                            <th>Kab/Kota</th>
                            <td>: {{ $evaluasi->city->name }}</td>
                        </tr>
                        <tr>
                            <th>Kecamatan</th>
                            <td>: {{ $evaluasi->district->name }}</td>
                        </tr>
                        <tr>
                            <th>Kawasan</th>
                            <td>: {{ $evaluasi->village->name }}</td>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="col-md-6">
                <table>
                    <thead>
                        <tr>
                            <th>Luas SK</th>
                            <td>: {{ $evaluasi->luas_kawasan }} Ha</td>
                        </tr>
                        <tr>
                            <th>Luas Verifikasi</th>
                            <td>: {{ $evaluasi->luas_kumuh }} Ha</td>
                        </tr>
                        <tr>
                            <th>Jumlah Bangunan</th>
                            <td>: {{ $evaluasi->jumlah_bangunan }} Unit</td>
                        </tr>
                        <tr>
                            <th>Jumlah Penduduk</th>
                            <td>: {{ $evaluasi->jumlah_penduduk }} Jiwa</td>
                        </tr>
                        <tr>
                            <th>Jumlah Bangunan</th>
                            <td>: {{ $evaluasi->jumlah_bangunan }} KK</td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2" class="align-middle text-center">ASPEK</th>
                    <th rowspan="2" class="align-middle text-center">KRITERIA</th>
                    <th rowspan="2" class="align-middle text-center">PARAMETER</th>
                    <th rowspan="2" class="align-middle text-center">SKOR</th>
                    <th colspan="4" class="text-center">KONDISI AWAL</th>
                </tr>
                <tr>
                    <th class="text-center">NUMERIK</th>
                    <th class="text-center">SATUAN</th>
                    <th class="text-center">PROSEN (%)</th>
                    <th class="text-center">NILAI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kriteria as $key => $value)
                @php $detailEvaluasi = evaluasiDetail($evaluasi->id,$value->kriteria_id); @endphp
                <tr>
                    <td rowspan="<?php echo count($detailEvaluasi)+1 ?>">{{ $value->nama_kriteria }}</td>
                </tr>
                    @if(isset($detailEvaluasi))
                        @foreach($detailEvaluasi as $x => $v)
                            @php $subkriteria = subkriteria($v->subkriteria_id); @endphp
                            <tr>
                                <td>{{ $v->nama_subkriteria }}</td>
                                <td>
                                    76%-100%
                                    <br/>
                                    51%-75%
                                    <br/>
                                    25%-50%
                                </td>
                                <td>
                                    5
                                    <br/>
                                    3
                                    <br/>
                                    1
                                </td>
                                <td>{{ $v->jawaban }}</td>
                                <td>{{ $subkriteria->satuan }}</td>
                                <td>{{ $v->persen }}</td>
                                <td>{{ $v->nilai }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="text-right">TOTAL NILAI</td>
                    <td>{{ $evaluasiKriteria ?? '0' }}</td>
                </tr>
                <tr>
                    <td colspan="7" class="text-right">TINGKAT KEKUMUHAN</td>
                    <td>{{ $statusEvaluasi ?? '-' }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>