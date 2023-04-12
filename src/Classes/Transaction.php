<?php

namespace App\Classes;

use App\Classes\Api\CardInfoApi\ApiCardInfoInterface;
use App\Classes\Api\ExchangeRateApi\ApiExchangeRateInterface;

class Transaction
{
    private int $cardBin;
    private int $amount;
    private string $currency;
    private ApiCardInfoInterface $apiCardInfo;
    private ApiExchangeRateInterface $apiExchangeRate;

    public function __construct(
        int $cardBin,
        int $amount,
        string $currency,
        ApiCardInfoInterface $apiCardInfo,
        ApiExchangeRateInterface $apiExchangeRate
    )
    {
        $this->cardBin = $cardBin;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->apiCardInfo = $apiCardInfo;
        $this->apiExchangeRate = $apiExchangeRate;

        if(!Config::$configInitialized) {
            Config::initializeConfig();
        }
    }

    public function calculateCommission(): float
    {
        $binResults = $this->apiCardInfo->extractCardInfo($this->cardBin);

        if(!$binResults) {
            return 0;
        }

        $baseCurrency = Config::$baseCurrency;

        $rate = $this->apiExchangeRate->extractExchangeRate($baseCurrency, $this->currency);

        if($rate == 0) {
            return 0;
        }

        $amountInEur = $this->amount / $rate;

        $commissionIndex = $this->getCommissionIndex($binResults->getCountry()->getAlpha2());

        return number_format($amountInEur * $commissionIndex, 2, '.', '');
    }

    function getCommissionIndex(string $country): float
    {
        return $this->isCountryFromEU($country) ? Config::$commissionIndexEu : Config::$commissionIndex;
    }

    public function isCountryFromEU(string $country): bool
    {
        $euCountries = Config::$euCountriesCodes;

        return in_array($country, $euCountries);
    }
}