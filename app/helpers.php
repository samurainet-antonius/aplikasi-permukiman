<?php
use Illuminate\Support\Facades\Auth;

if(!function_exists('says')){
    function says(){

        $h = date('G');

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