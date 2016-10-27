<?php

function convertBtcToSatoshi($value)
{
    return $value * 100000000;
}

function printBTC($value)
{
    return number_format(($value) * (pow(10, -8)), 8, '.', '');
}

function printDolar($value)
{
    return number_format($value, 2, '.', '');
}

function convertSatoshiToBtc($value)
{
    return $value/100000000;
}

function checkCurrency($currency) {
    $currencys = file('http://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.txt');
    foreach ($currencys as $v) {
        $h = explode("|", $v);
        if ((count($h) >= 5) && ($h[3] == $currency)) {
            return str_replace(",", '.', $h[4]);
        }
    }
}

function templateStyle()
{
    $template = '<style>
        #mail { width: 600px; margin: 0 auto; position: relative; }
        table { border-collapse: collapse; border: 1px solid #aaa; width: 100%; }
        table td, table th { border-collapse: collapse; border: 1px solid #aaa; padding: 5px; margin: 0; }
        table td { text-align: right; }
        table th { background: #eee; text-align: left; }
        table th.date { width: 80px; }
    </style>';
    return $template;
}