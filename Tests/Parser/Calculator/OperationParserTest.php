<?php declare(strict_types = 1);

namespace Tests\Parser\Calculator;

use Source\Exception\FileNotFoundException;
use Source\Parser\Calculator\CommissionCalculator;
use Source\Parser\OperationParser;
use PHPUnit\Framework\TestCase;
use Source\Parser\Output\Output;

class OperationParserTest extends TestCase
{
    private $operationParser;
    
    protected function setUp()
    {
        $commissionCalculator  = $this->createMock(CommissionCalculator::class);
        $this->operationParser = new OperationParser(new Output(), $commissionCalculator);
    }
    
    /**
     * @param $input
     * @param $expected
     *
     * @dataProvider parseLineDataProvider
     */
    public function testParseLine($input, $expected)
    {
        $this->assertEquals($expected, $this->operationParser->parseLine($input));
    }
    
    public function parseLineDataProvider(): array
    {
        return [
            [
                ['2014-12-31', '4', 'natural', 'cash_out', '1200.00', 'EUR'],
                [
                    'date'               => '2014-12-31',
                    'user_id'            => '4',
                    'user_type'          => 'natural',
                    'operation_type'     => 'cash_out',
                    'operation_amount'   => '1200.00',
                    'operation_currency' => 'EUR',
                ],
            ],
            [
                ['2015-01-01', '4', 'natural', 'cash_out', '1000.00', 'EUR'],
                [
                    'date'               => '2015-01-01',
                    'user_id'            => '4',
                    'user_type'          => 'natural',
                    'operation_type'     => 'cash_out',
                    'operation_amount'   => '1000.00',
                    'operation_currency' => 'EUR',
                ],
            ],
        ];
    }
    
    public function testGetInputHandle()
    {
        $this->assertInstanceOf(
            \SplFileObject::class,
            $this->operationParser->getInputHandle(ROOT_PATH . '/input.csv')
        );
    }
    
    public function testGetInputHandleException()
    {
        $this->expectException(FileNotFoundException::class);
        $this->operationParser->getInputHandle('a.csv');
    }
}
