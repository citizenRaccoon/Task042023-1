<?php

namespace App\Classes\Models;

class Country
{
    private int $numeric;
    private string $alpha2;
    private string $name;
    private string $currency;

    public function __construct(
        int $numeric,
        string $alpha2,
        string $name,
        string $currency,
    )
    {
        $this->numeric = $numeric;
        $this->alpha2 = $alpha2;
        $this->name = $name;
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getNumeric(): int
    {
        return $this->numeric;
    }

    /**
     * @return string
     */
    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}