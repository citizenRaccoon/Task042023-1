<?php

namespace App\Classes\Api\ExchangeRateApi;

use App\Classes\Config;

class ApiLayerExchangeRate implements ApiExchangeRateInterface
{
    private string $token;

    public function __construct()
    {
        if(!Config::$configInitialized) {
            Config::initializeConfig();
        }
        $this->token = Config::$apiExchangeRateToken;
    }

    public function extractExchangeRate(string $baseCurrency, $neededCurrency, $rateDate = NULL): float
    {
        $response = $this->getApiResponse($neededCurrency, $baseCurrency, $rateDate);
        $response = json_decode($response, true);
        if(!$response['rates'][$neededCurrency]) {
            return 0;
        }
        return $response['rates'][$neededCurrency];
    }

    /**
     * @param $neededCurrency
     * @param string $baseCurrency
     * @param mixed $rateDate
     * @return bool|string
     */
    public function getApiResponse($neededCurrency, string $baseCurrency, string $rateDate = NULL): string|bool
    {
        $curl = curl_init();

        if (empty($rateDate)) {
            $rateDate = 'latest';
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://Api.apilayer.com/exchangerates_data/$rateDate?symbols=$neededCurrency&base=$baseCurrency",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: $this->token"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}