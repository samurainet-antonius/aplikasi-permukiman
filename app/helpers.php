<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if(!function_exists('says')){
    function says(){
        date_default_timezone_set("Asia/Jakarta");  
        $h = date('H:i:s');

        if($h>=5 && $h<=11)
        {
            return "Selamat pagi";
        }
        else if($h>=12 && $h<=15)
        {
            return "Selamat siang";
        }
        else
        {
            return "Selamat malam";
        }
    }
}

function siteSetting($column){
    $result = DB::table('settings')->where('name',$column)->first()->payload;
    $result = str_replace('"',"",$result);
    $result = str_replace("public","",$result);
    return $result;

}