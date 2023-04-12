<?php

namespace App\Classes\Models;

class CardInfo
{
    private string $type;
    private bool $prepaid;
    private Country $country;

    public function __construct(string $type, bool $prepaid, Country $country)
    {
        $this->type = $type;
        $this->country = $country;
        $this->prepaid = $prepaid;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isPrepaid(): bool
    {
        return $this->prepaid;
    }

    /**
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }


}