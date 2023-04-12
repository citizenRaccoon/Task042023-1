<?php

require __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Config;
use App\Classes\Transaction;

foreach (explode("\n", file_get_contents($argv[1])) as $row) {

    $data = json_decode($row, true);
    Config::initializeConfig();
    $apiCardInfoClass = 'App\\Classes\\Api\\CardInfoApi\\' . Config::$apiCardInfoClass;
    $apiExchangeRateClass = 'App\\Classes\\Api\\ExchangeRateApi\\' . Config::$apiExchangeRateClass;
    $apiCardInfo = new $apiCardInfoClass;
    $apiExchangeRate = new $apiExchangeRateClass;
    $transaction = new Transaction(
        $data['bin'],
        $data['amount'],
        $data['currency'],
        $apiCardInfo,
        $apiExchangeRate
    );
    $commission = $transaction->calculateCommission();
    if($commission > 0) {
        print $commission;
        print "\n";
    } else {
        error_log("[" . date(DATE_ATOM) . "]Can\'t calculate commission due to errors 
        in input data ($row) or issues of APIs.", 3, __DIR__ . 'errors.log');
    }
}