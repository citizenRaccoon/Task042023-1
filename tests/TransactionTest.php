<?php

namespace Tests;

use App\Classes\Api\CardInfoApi\ApiCardInfoInterface;
use App\Classes\Api\CardInfoApi\BinlistApiCardInfo;
use App\Classes\Api\ExchangeRateApi\ApiExchangeRateInterface;
use App\Classes\Api\ExchangeRateApi\ApiLayerExchangeRate;
use App\Classes\Models\Country;
use App\Classes\Models\CardInfo;
use App\Classes\Config;
use App\Classes\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testCalculateCommission()
    {
        Config::initializeConfig();

        $cardInfoMocked = new CardInfo(
            'debit',
            false,
            new Country(
                208,
                'DK',
                'Denmark',
                'DKK'
            )
        );
        $exchangeRateMocked = 1.00;
        $apiCardInfoMock = $this->createMock(ApiCardInfoInterface::class);
        $apiCardInfoMock->expects($this->once())
            ->method('extractCardInfo')
            ->willReturn($cardInfoMocked);

        $apiExchangeRateMock = $this->createMock(ApiExchangeRateInterface::class);
        $apiExchangeRateMock->expects($this->once())
            ->method('extractExchangeRate')
            ->willReturn($exchangeRateMocked);

        $transactionTest = new Transaction(
            45717360,
            100,
            'EUR',
            $apiCardInfoMock,
            $apiExchangeRateMock
        );
        $result = $transactionTest->calculateCommission();

        $this->assertEquals(1, $result);
    }

    public function testCalculateCommissionRounding()
    {
        Config::initializeConfig();

        $cardInfoMocked = new CardInfo(
            'debit',
            false,
            new Country(
                826,
                'GB',
                'United Kingdom of Great Britain and Northern Ireland',
                'GBP'
            )
        );
        $exchangeRateMocked = 0.880806;
        $apiCardInfoMock = $this->createMock(ApiCardInfoInterface::class);
        $apiCardInfoMock->expects($this->once())
            ->method('extractCardInfo')
            ->willReturn($cardInfoMocked);

        $apiExchangeRateMock = $this->createMock(ApiExchangeRateInterface::class);
        $apiExchangeRateMock->expects($this->once())
            ->method('extractExchangeRate')
            ->willReturn($exchangeRateMocked);

        $transactionTest = new Transaction(
            4745030,
            2000.00,
            'GBP',
            $apiCardInfoMock,
            $apiExchangeRateMock
        );
        $result = $transactionTest->calculateCommission();

        $this->assertEquals(45.41, $result);
    }

    public function testGetCommissionIndex()
    {
        Config::initializeConfig();
        $transaction = new Transaction(
            0,
            0,
            'EUR',
            new BinlistApiCardInfo(),
            new ApiLayerExchangeRate()
        );
        $this->assertEquals(Config::$commissionIndexEu, $transaction->getCommissionIndex('AT'));
        $this->assertEquals(Config::$commissionIndex, $transaction->getCommissionIndex('US'));
    }

    public function testIsCountryFromEU()
    {
        $transaction = new Transaction(
            0,
            0,
            'EUR',
            new BinlistApiCardInfo(),
            new ApiLayerExchangeRate()
        );
        $this->assertTrue($transaction->isCountryFromEU('AT'));
        $this->assertFalse($transaction->isCountryFromEU('US'));
    }
}
