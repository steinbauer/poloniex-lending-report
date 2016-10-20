<?php

echo 'WIP, sorry<hr />';

//read config
if(!is_file('config.php')) {
    die('fatal error: config.php not exist');
}
require_once 'config.php';

//connect to poloniex.com
require_once 'Libs/poloniex.php';
$poloniex = new Poloniex($key, $api_secret);

//test connect
//$volume = $poloniex->get_volume();
//print_r($volume);

$lendingHistory = $poloniex->get_lending_history();

echo "<pre>\n";
print_r($lendingHistory);

//loading deposits

//loading lendings

//data processing

//email template

//sending or printing