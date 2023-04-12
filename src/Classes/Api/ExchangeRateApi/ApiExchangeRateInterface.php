<?php

namespace App\Classes\Api\ExchangeRateApi;

interface ApiExchangeRateInterface
{
    public function extractExchangeRate(string $baseCurrency, $neededCurrency, $rateDate = NULL) : float;
}