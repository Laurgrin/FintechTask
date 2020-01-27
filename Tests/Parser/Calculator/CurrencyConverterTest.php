<?php declare(strict_types = 1);

namespace Tests\Parser\Calculator;

use Source\Model\Money\Money;
use Source\Parser\Calculator\CurrencyConverter;
use PHPUnit\Framework\TestCase;

class CurrencyConverterTest extends TestCase
{
    private $currencyConverter;
    
    protected function setUp()
    {
        $this->currencyConverter = $this->getMockBuilder(CurrencyConverter::class)
                                        ->setMethodsExcept(['convert'])
                                        ->getMock();
        
        $this->currencyConverter->method('getCurrencyData')
                                ->willReturn(
                                    [
                                        'EUR' => [
                                            'precision'  => 2,
                                            'conversion' => [
                                                'USD' => '1.1497',
                                                'JPY' => '129.53',
                                            ],
                                        ],
                                        'USD' => [
                                            'precision'  => 2,
                                            'conversion' => [
                                                'EUR' => '0.8697',
                                            ],
                                        ],
                                        'JPY' => [
                                            'precision'  => 0,
                                            'conversion' => [
                                                'EUR' => '0.0077',
                                            ],
                                        ],
                                    ]
                                );
    }
    
    /**
     * @param $money
     * @param $targetCurrency
     * @param $expected
     *
     * @dataProvider convertDataProvider
     * @throws \Source\Exception\FileNotFoundException
     */
    public function testConvert($money, $targetCurrency, $expected)
    {
        $this->assertEquals($expected, $this->currencyConverter->convert($money, $targetCurrency));
    }
    
    public function convertDataProvider(): array
    {
        $eur = new Money();
        $eur->setAmount('1000')->setCurrencyName('EUR');
    
        $usd = new Money();
        $usd->setAmount('1000')->setCurrencyName('USD');
    
        $jpy = new Money();
        $jpy->setAmount('1000')->setCurrencyName('JPY');
        
        $eurToEur = new Money();
        $eurToEur->setCurrencyName('EUR')->setAmount('1000');
    
        $usdToUsd = new Money();
        $usdToUsd->setCurrencyName('USD')->setAmount('1000');
    
        $jpyToJpy = new Money();
        $jpyToJpy->setCurrencyName('JPY')->setAmount('1000');
    
        $eurToUsd = new Money();
        $eurToUsd->setCurrencyName('USD')->setAmount('1149.7');
    
        $eurToJpy = new Money();
        $eurToJpy->setCurrencyName('JPY')->setAmount('129530');
    
        $usdToEur = new Money();
        $usdToEur->setCurrencyName('EUR')->setAmount('869.7');
    
        $jpyToEur = new Money();
        $jpyToEur->setCurrencyName('EUR')->setAmount('7.7');
        
        return [
            'EUR to EUR' => [clone($eur), 'EUR', $eurToEur],
            'USD to USD' => [clone($usd), 'USD', $usdToUsd],
            'JPY to JPY' => [clone($jpy), 'JPY', $jpyToJpy],
            'EUR to USD' => [clone($eur), 'USD', $eurToUsd],
            'EUR to JPY' => [clone($eur), 'JPY', $eurToJpy],
            'USD to EUR' => [clone($usd), 'EUR', $usdToEur],
            'JPY to EUR' => [clone($jpy), 'EUR', $jpyToEur],
        ];
    }
}
