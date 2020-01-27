<?php declare(strict_types = 1);

namespace Tests\Parser\Calculator;

use Source\Model\Money\Money;
use Source\Model\Operation\Operation;
use Source\Parser\Calculator\CommissionCalculator;
use PHPUnit\Framework\TestCase;
use Source\Parser\Calculator\CurrencyConverter;
use Source\Parser\Calculator\Math;
use Source\Parser\Calculator\OperationManager;

class CommissionCalculatorTest extends TestCase
{
    private $commissionCalculator;
    
    protected function setUp()
    {
        $currencyConverter = $this->getMockBuilder(CurrencyConverter::class)
                                  ->setMethodsExcept(['convert'])
                                  ->getMock();
        $currencyConverter->method('getCurrencyData')
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
        
        $math             = new Math($currencyConverter);
        $operationManager = new OperationManager($math);
        
        $this->commissionCalculator = new CommissionCalculator($operationManager, $math);
    }
    
    /**
     * @dataProvider getCommissionsAmountDataProvider
     *
     * @param $operations
     * @param $type
     * @param $expected
     *
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws \Source\Exception\ContainerException
     * @throws \Source\Exception\FileNotFoundException
     * @throws \Source\Exception\OperationTypeException
     * @throws \Source\Exception\UserTypeException
     */
    public function testGetCommissionAmount($operations, $type, $expected)
    {
        $actual = $this->commissionCalculator->getCommissionAmount($operations, $type);
        $this->assertEquals($expected, $actual);
    }
    
    public function getCommissionsAmountDataProvider(): array
    {
        $op1    = new Operation();
        $money1 = new Money();
        $money1->setCurrencyName('EUR')->setAmount('1200.00');
        $op1->setType('cash_out')->setDate('2014-12-31')->setMoney($money1);
        $expectation1 = new Money();
        $expectation1->setAmount('0.60')->setCurrencyName('EUR');
        
        $op2    = new Operation();
        $money2 = new Money();
        $money2->setCurrencyName('EUR')->setAmount('1000.00');
        $op2->setType('cash_out')->setDate('2015-01-01')->setMoney($money2);
        $expectation2 = new Money();
        $expectation2->setAmount('3.00')->setCurrencyName('EUR');
        
        $op3    = new Operation();
        $money3 = new Money();
        $money3->setCurrencyName('EUR')->setAmount('1000.00');
        $op3->setType('cash_out')->setDate('2015-01-05')->setMoney($money3);
        $expectation3 = new Money();
        $expectation3->setAmount('0.00')->setCurrencyName('EUR');
        
        $cashInSmall = new Operation();
        $money4      = new Money();
        $money4->setCurrencyName('EUR')->setAmount('200.00');
        $cashInSmall->setType('cash_in')->setDate('2015-01-05')->setMoney($money4);
        $expectation4 = new Money();
        $expectation4->setAmount('0.06')->setCurrencyName('EUR');
        
        $cashInLarge = new Operation();
        $money5      = new Money();
        $money5->setCurrencyName('EUR')->setAmount('1000000.00');
        $cashInLarge->setType('cash_in')->setDate('2016-01-10')->setMoney($money5);
        $expectation5 = new Money();
        $expectation5->setAmount('5.00')->setCurrencyName('EUR');
        
        $cashInLarge = new Operation();
        $money5      = new Money();
        $money5->setCurrencyName('EUR')->setAmount('1000000.00');
        $cashInLarge->setType('cash_in')->setDate('2016-01-10')->setMoney($money5);
        $expectation5 = new Money();
        $expectation5->setAmount('5.00')->setCurrencyName('EUR');
        
        $cashOutLegal = new Operation();
        $money6       = new Money();
        $money6->setCurrencyName('EUR')->setAmount('300.00');
        $cashOutLegal->setType('cash_out')->setDate('2016-01-06')->setMoney($money6);
        $expectation6 = new Money();
        $expectation6->setAmount('0.90')->setCurrencyName('EUR');
        
        $op7    = new Operation();
        $money7 = new Money();
        $money7->setCurrencyName('JPY')->setAmount('30000');
        $op7->setType('cash_out')->setDate('2016-01-06')->setMoney($money7);
        $expectation7 = new Money();
        $expectation7->setAmount('0')->setCurrencyName('JPY');
        
        $op8    = new Operation();
        $money8 = new Money();
        $money8->setCurrencyName('EUR')->setAmount('1000.00');
        $op8->setType('cash_out')->setDate('2016-01-07')->setMoney($money8);
        $expectation8 = new Money();
        $expectation8->setAmount('0.69')->setCurrencyName('EUR');
        
        $op9    = new Operation();
        $money9 = new Money();
        $money9->setCurrencyName('USD')->setAmount('100.00');
        $op9->setType('cash_out')->setDate('2016-01-07')->setMoney($money9);
        $expectation9 = new Money();
        $expectation9->setAmount('0.30')->setCurrencyName('USD');
        
        $op10    = new Operation();
        $money10 = new Money();
        $money10->setCurrencyName('EUR')->setAmount('100.00');
        $op10->setType('cash_out')->setDate('2016-01-07')->setMoney($money10);
        $expectation10 = new Money();
        $expectation10->setAmount('0.30')->setCurrencyName('EUR');
        
        return [
            'cash out over the discount' => [[$op1], 'natural', $expectation1],
            'second cash out same week'  => [[$op1, $op2], 'natural', $expectation2],
            'cash out equals discount'   => [[$op1, $op2, $op3], 'natural', $expectation3],
            'cash in small'              => [[$cashInSmall], 'natural', $expectation4],
            'cash in large'              => [[$cashInLarge], 'legal', $expectation5],
            'cash out legal'             => [[$cashOutLegal], 'legal', $expectation6],
            'cash out conversion 1'      => [[$op7], 'natural', $expectation7],
            'cash out conversion 2'      => [[$op7, $op8], 'natural', $expectation8],
            'cash out conversion 3'      => [[$op7, $op8, $op9], 'natural', $expectation9],
            'cash out conversion 4'      => [[$op7, $op8, $op9, $op10], 'natural', $expectation10],
        ];
    }
}
