<?php

function get_year_and_quarter_string() {
    $month = date('n');
    $year = date('Y');
    $quarter = 0;

    switch ($month) {
        case $month < 4:
            $quarter = 1;
            break;
        case $month < 7:
            $quarter = 2;
            break;
        case $month < 10:
            $quarter = 3;
            break;
        case $month < 13:
            $quarter = 4;
            break;
        default:
            break;
    }

    return "$year-Q$quarter";
}
