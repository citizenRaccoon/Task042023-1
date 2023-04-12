<?php

namespace App\Classes\Api\CardInfoApi;

use App\Classes\Models\Country;
use App\Classes\Models\CardInfo;

class BinlistApiCardInfo implements ApiCardInfoInterface
{
    private string $url = "https://lookup.binlist.net/";

    public function extractCardInfo(int $cardPrefix): CardInfo|null
    {
        $response = $this->getApiResponse($cardPrefix);
        if(empty($response)) {
            return null;
        }
        $response = json_decode($response, true);
        return new CardInfo(
            empty($response['type']) ? '' : $response['type'],
            !empty($response['prepaid']),
            new Country(
                $response['country']['numeric'],
                $response['country']['alpha2'],
                $response['country']['name'],
                $response['country']['currency']
            )
        );
    }

    /**
     * @param int $cardPrefix
     * @return string|bool
     */
    private function getApiResponse(int $cardPrefix): string|bool
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->url . $cardPrefix);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}