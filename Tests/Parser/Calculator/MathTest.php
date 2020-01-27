<?php declare(strict_types = 1);

namespace Paysera\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Source\Model\Money\Money;
use Source\Parser\Calculator\CurrencyConverter;
use Source\Parser\Calculator\Math;

class MathTest extends TestCase
{
    /**
     * @var Math
     */
    private $math;
    
    public function setUp()
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
                                      'precision'  => 2,
                                      'conversion' => [
                                          'EUR' => '0.0077',
                                      ],
                                  ],
                              ]
                          );
        $this->math = new Math($currencyConverter);
    }
    
    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForAddTesting
     * @throws \Source\Exception\FileNotFoundException
     */
    public function testAdd(string $leftOperand, string $rightOperand, string $expectation)
    {
        $money = new Money();
        $money->setAmount($leftOperand)->setCurrencyName('EUR');
        
        $this->assertEquals(
            $expectation,
            $this->math->add($money, $rightOperand)->getAmount()
        );
    }
    
    public function dataProviderForAddTesting(): array
    {
        return [
            'add 2 natural numbers'             => ['1', '2', '3'],
            'add negative number to a positive' => ['-1', '2', '1'],
            'add natural number to a float'     => ['1', '1.05123', '2.05'],
        ];
    }
    
    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForMultiplyTesting
     * @throws \Source\Exception\FileNotFoundException
     */
    public function testMultiply(string $leftOperand, string $rightOperand, string $expectation)
    {
        $money = new Money();
        $money->setAmount($leftOperand)->setCurrencyName('EUR');
        
        $this->assertEquals(
            $expectation,
            $this->math->multiply($money, $rightOperand)->getAmount()
        );
    }
    
    public function dataProviderForMultiplyTesting(): array
    {
        return [
            'multiply 2 natural numbers'               => ['2', '3', '6'],
            'multiply negative number with a positive' => ['-1', '2', '-2'],
            'multiply natural number with a float'     => ['3', '1.05123', '3.15'],
        ];
    }
    
    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForSubtractTesting
     * @throws \Source\Exception\FileNotFoundException
     */
    public function testSubtract(string $leftOperand, string $rightOperand, string $expectation)
    {
        $money = new Money();
        $money->setAmount($leftOperand)->setCurrencyName('EUR');
        
        $this->assertEquals(
            $expectation,
            $this->math->subtract($money, $rightOperand)->getAmount()
        );
    }
    
    public function dataProviderForSubtractTesting(): array
    {
        return [
            'subtract 2 natural numbers'               => ['3', '2', '1'],
            'subtract negative number from a positive' => ['-1', '2', '-3'],
            'subtract natural number from a float'     => ['3.545', '1', '2.55'],
        ];
    }
}
