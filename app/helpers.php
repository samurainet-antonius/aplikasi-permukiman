<?php
use Illuminate\Support\Facades\Auth;

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