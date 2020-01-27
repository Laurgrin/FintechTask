<?php declare(strict_types = 1);

namespace Tests\Parser\Calculator;

use Source\Model\Money\Money;
use Source\Model\Operation\Operation;
use Source\Parser\Calculator\CurrencyConverter;
use Source\Parser\Calculator\Math;
use Source\Parser\Calculator\OperationManager;
use PHPUnit\Framework\TestCase;

class OperationManagerTest extends TestCase
{
    
    private $operationManager;
    
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
        
        $math = new Math($currencyConverter);
        
        $this->operationManager = new OperationManager($math);
    }
    
    /**
     * @param $operations
     * @param $sum
     * @param $amount
     * @param $expected
     *
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws \Source\Exception\ContainerException
     * @throws \Source\Exception\FileNotFoundException
     * @dataProvider getAmountAfterDiscountDataProvider
     */
    public function testGetAmountAfterDiscount($operations, $sum, $amount, $expected)
    {
        $this->assertEquals($expected, $this->operationManager->getAmountAfterDiscount($operations, $sum, $amount));
    }
    
    public function getAmountAfterDiscountDataProvider(): array
    {
        $op1    = new Operation();
        $money1 = new Money();
        $money1->setCurrencyName('EUR')->setAmount('1200.00');
        $sum1 = new Money();
        $sum1->setCurrencyName('EUR')->setAmount('0');
        $op1->setDate('2014-12-31')->setType('cash_out')->setMoney($money1);
        $expectation1 = new Money();
        $expectation1->setCurrencyName('EUR')->setAmount('200.00');
        
        $op2    = new Operation();
        $money2 = new Money();
        $money2->setCurrencyName('EUR')->setAmount('1000.00');
        $sum2 = new Money();
        $sum2->setCurrencyName('EUR')->setAmount('1200.00');
        $op2->setDate('2015-01-01')->setType('cash_out')->setMoney($money2);
        $expectation2 = new Money();
        $expectation2->setCurrencyName('EUR')->setAmount('1000.00');
        
        $op3    = new Operation();
        $money3 = new Money();
        $money3->setCurrencyName('EUR')->setAmount('1000.00');
        $sum3 = new Money();
        $sum3->setCurrencyName('EUR')->setAmount('0');
        $op3->setDate('2016-01-05')->setType('cash_out')->setMoney($money3);
        $expectation3 = new Money();
        $expectation3->setCurrencyName('EUR')->setAmount('0.00');
        
        $op4    = new Operation();
        $money4 = new Money();
        $money4->setCurrencyName('JPY')->setAmount('30000');
        $sum4 = new Money();
        $sum4->setCurrencyName('EUR')->setAmount('0');
        $op4->setDate('2016-01-06')->setType('cash_out')->setMoney($money4);
        $expectation4 = new Money();
        $expectation4->setCurrencyName('EUR')->setAmount('0.00');
        
        $op5    = new Operation();
        $money5 = new Money();
        $money5->setCurrencyName('EUR')->setAmount('1000.00');
        $sum5 = new Money();
        $sum5->setCurrencyName('EUR')->setAmount('231.61');
        $op5->setDate('2016-01-06')->setType('cash_out')->setMoney($money5);
        $expectation5 = new Money();
        $expectation5->setCurrencyName('EUR')->setAmount('231.61');
        
        return [
            'no previous operations'                     => [[$op1], $sum1, $money1, $expectation1],
            'one previous operations'                    => [[$op1, $op2], $sum2, $money2, $expectation2],
            'previous operations on same week last year' => [[$op1, $op2, $op3], $sum3, $money3, $expectation3],
            'conversion'                                 => [[$op4], $sum4, $money4, $expectation4],
            'after conversion'                           => [[$op5], $sum5, $money5, $expectation5],
        ];
    }
    
    /**
     * @param $input
     * @param $expected
     *
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws \Source\Exception\ContainerException
     * @throws \Source\Exception\FileNotFoundException
     * @dataProvider sumCashOutOperationsDataProvider
     */
    public function testSumCashOutOperations($input, $expected)
    {
        $this->assertEquals($expected, $this->operationManager->sumCashOutOperations($input));
    }
    
    public function sumCashOutOperationsDataProvider(): array
    {
        $op1    = new Operation();
        $money1 = new Money();
        $money1->setCurrencyName('EUR')->setAmount('1200.00');
        $op1->setDate('2014-12-31')->setType('cash_out')->setMoney($money1);
        
        $expectation1 = new Money();
        $expectation1->setCurrencyName('EUR')->setAmount('0');
        
        $expectation2 = new Money();
        $expectation2->setCurrencyName('EUR')->setAmount('1200');
        
        return [
            'no previous operations' => [[], $expectation1],
            'one previous operation' => [[$op1], $expectation2],
        ];
    }
    
    /**
     * @dataProvider getOperationsInSameWeekDataProvider
     *
     * @param $input
     * @param $expected
     */
    public function testGetOperationsInSameWeek($input, $expected)
    {
        $this->assertEquals($expected, $this->operationManager->getOperationsInSameWeek($input));
    }
    
    public function getOperationsInSameWeekDataProvider(): array
    {
        $op1 = new Operation();
        $op1->setDate('2014-12-31')->setType('cash_out');
        
        $op2 = new Operation();
        $op2->setDate('2015-01-01')->setType('cash_out');
        
        $op3 = new Operation();
        $op3->setDate('2016-01-05')->setType('cash_out');
        
        return [
            'no previous operations'                     => [[$op1], []],
            'one previous operations'                    => [[$op1, $op2], [$op1]],
            'previous operations on same week last year' => [[$op1, $op2, $op3], []],
        ];
    }
}
