<?php
//read config and function
if (!is_file('config.php')) {
    die('fatal error: config.php not exist');
}
require_once 'config.php';
require_once 'function.php';

//connect to poloniex.com
require_once 'Libs/poloniex.php';
$poloniex = new Poloniex($key, $api_secret);

//loading deposits

//loading lendings
$lendingHistory = $poloniex->get_lending_history();
//echo '<pre>'; print_r($lendingHistory);

$balanceAll = $poloniex->get_balances();
$balance = $balanceAll["BTC"];


//echo('<pre>'); print_r($poloniex->get_balances());

//data processing
$lendingsSuma = 0;
$lendingsDay = array();
$lendingsMonth = array();
$lendingsYear = array();
foreach ($lendingHistory AS $oneLending) {
    if ($oneLending["currency"] == "BTC") {
        $day = date('Ymd', strtotime($oneLending["close"]));
        $month = date('Ym', strtotime($oneLending["close"]));
        $year = date('Y', strtotime($oneLending["close"]));
        if (isset($lendingsDay[$day])) {
            $lendingsDay[$day] += convertBtcToSatoshi($oneLending["earned"]);
        } else {
            $lendingsDay[$day] = convertBtcToSatoshi($oneLending["earned"]);
        }
        if (isset($lendingsMonth[$month])) {
            $lendingsMonth[$month] += convertBtcToSatoshi($oneLending["earned"]);
        } else {
            $lendingsMonth[$month] = convertBtcToSatoshi($oneLending["earned"]);
        }
        if (isset($lendingsYear[$year])) {
            $lendingsYear[$year] += convertBtcToSatoshi($oneLending["earned"]);
        } else {
            $lendingsYear[$year] = convertBtcToSatoshi($oneLending["earned"]);
        }
        $lendingsSuma += convertBtcToSatoshi($oneLending["earned"]);
    }
}

//email template
$template = '<html><head><title>Poloniex: lending report</title></head><body><div id="mail">';
$template .= 'Work in progress, sorry<hr />';
$template .= '<h1>Poloniex: lending report</h1>';
$template .= 'Balance: ' . printBTC(convertBtcToSatoshi($balance)) . ' BTC<br />';
$template .= templateStyle();

//day
$countDay = min($lastDay, count($lendingsDay));
if ($countDay > 0) {
    $template .= '<h2>last ' . $countDay . ' day(s)</h2>';
    $template .= '<table><tr><th class="date">date</th><th>BTC</th><th>USD (today\'s rate)</th></tr>';
    $i = 0;
    foreach ($lendingsDay AS $date => $oneLending) {
        $template .= '<tr><td>' . date($formatDateDay, strtotime($date)) . '</td><td>' . printBTC($oneLending) . '</td><td>-</td></tr>';
        $i++;
        if ($i >= $countDay) break;
    }
    $template .= '</table>';
}

//month
if (count($lendingsMonth) > 0) {
    $template .= '<h2>last ' . count($lendingsMonth) . ' month(s)</h2>';
    $template .= '<table><tr><th class="date">date</th><th>BTC</th><th>USD (today\'s rate)</th></tr>';
    foreach ($lendingsMonth AS $date => $oneLending) {
        $template .= '<tr><td>' . date($formatDateMonth, strtotime($date)) . '</td><td>' . printBTC($oneLending) . '</td><td>-</td></tr>';
    }
    $template .= '</table>';
}

//year
if (count($lendingsYear) > 0) {
    $template .= '<h2>last ' . count($lendingsYear) . ' year(s)</h2>';
    $template .= '<table><tr><th class="date">date</th><th>BTC</th><th>USD (today\'s rate)</th></tr>';
    foreach ($lendingsYear AS $date => $oneLending) {
        $template .= '<tr><td>' . date("Y", strtotime($date)) . '</td><td>' . printBTC($oneLending) . '</td><td>-</td></tr>';
    }
    $template .= '</table>';
}

if (count($lendingsYear) > 1) {
    $template .= '<h2>Lending sum</h2>';
    $template .= '<table><tr><th class="date">date</th><th>BTC</th><th>USD (today\'s rate)</th></tr>';
    $template .= '<tr><td>sum</td><td>' . printBTC($lendingsSuma) . '</td><td>-</td></tr>';
    $template .= '</table>';
}


$template .= '</div></body>';

//sending or printing
//echo '<pre>'; print_r($lendingsDay);
//echo '<pre>'; print_r($lendingsMonth);
//echo '<pre>'; print_r($lendingsYear);
//echo '<pre>'; print_r($lendingsSuma);
echo $template;