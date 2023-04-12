<?php

namespace App\Classes;

class Config
{
    public static bool $configInitialized = false;
    public static string $baseCurrency;
    public static float $commissionIndex;
    public static float $commissionIndexEu;
    public static string $apiCardInfoClass;
    public static string $apiCardInfoToken;
    public static string $apiExchangeRateClass;
    public static string $apiExchangeRateToken;
    public static array $euCountriesCodes;

    public static function initializeConfig(): void
    {
        $configJson = file_get_contents(__DIR__ . '/../../config/config.json');
        $config = json_decode($configJson, true);

        self::$baseCurrency = $config['calculation_params']['base_currency'];
        self::$commissionIndex = $config['calculation_params']['commission_index'];
        self::$commissionIndexEu = $config['calculation_params']['commission_index_eu'];

        self::$apiCardInfoClass = $config['api_client_params']['ApiCardInfo_class'];
        self::$apiCardInfoToken = $config['api_client_params']['ApiCardInfo_token'];
        self::$apiExchangeRateClass = $config['api_client_params']['ApiExchangeRate_class'];
        self::$apiExchangeRateToken = $config['api_client_params']['ApiExchangeRate_token'];

        self::$euCountriesCodes = $config['europe_countries_codes'];

        self::$configInitialized = true;
    }

}