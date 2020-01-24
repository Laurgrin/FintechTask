<?php declare(strict_types = 1);

namespace Source\Parser\Calculator;

use Source\Model\Money\MoneyInterface;

interface CalculatorInterface
{
    /**
     * Multiplies a money value
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $amount
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function multiply(MoneyInterface $money, string $amount): MoneyInterface;
    
    /**
     * Sums the money object amounts in EUR currency.
     *
     * @param array $money
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function sumCashOutOperations(array $money): MoneyInterface;
    
    /**
     * Subtracts the specified sum from the money object
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $amount
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function subtract(MoneyInterface $money, string $amount): MoneyInterface;
}