<?php

function rgbColor(){
    $color1 = rand(0,255);
    $color2 = rand(0,255);
    $color3 = rand(0,255);
    $rgb = "$color1,$color2,$color3";

    return $rgb;
}
