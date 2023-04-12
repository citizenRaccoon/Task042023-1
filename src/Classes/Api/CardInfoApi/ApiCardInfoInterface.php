<?php

namespace App\Classes\Api\CardInfoApi;

use App\Classes\Models\CardInfo;

interface ApiCardInfoInterface
{
    public function extractCardInfo(int $cardPrefix) : CardInfo|null;
}