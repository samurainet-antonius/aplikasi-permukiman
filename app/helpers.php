<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if (!function_exists('says')) {
    function says()
    {
        date_default_timezone_set("Asia/Jakarta");
        $h = date('H');

        if ($h >= 5 && $h <= 11) {
            return "Selamat pagi";
        } else if ($h >= 12 && $h <= 15) {
            return "Selamat siang";
        } else {
            return "Selamat malam";
        }
    }
}

function siteSetting($column)
{
    $result = DB::table('settings')->where('name', $column)->first()->payload;
    $result = str_replace('"', "", $result);
    $result = str_replace("public", "", $result);
    return $result;
}

function formulaKriteria($number)
{

    $status = DB::table('status_kriteria')->get();

    $statusKriteria = '';
    foreach ($status as $key => $value) {
        if ($value->nilai_min <= $number && $value->nilai_max >= $number) {
            $statusKriteria = $value->nama;
        }
    }

    return $statusKriteria;
}

function evaluasiDetail($evaluasiID, $kriteriaID)
{

    $evaluasiDetail = DB::table('evaluasi_detail')
        ->where([
            ['evaluasi_id', $evaluasiID],
            ['kriteria_id', $kriteriaID],
        ])
        ->get();

    return $evaluasiDetail;
}

function subKriteria($subkriteriaID)
{

    $subkriteria = DB::table('subkriteria')
        ->where('id', $subkriteriaID)
        ->first();

    return $subkriteria;
}
