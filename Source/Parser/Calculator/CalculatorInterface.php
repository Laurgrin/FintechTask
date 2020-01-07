<?php

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
     * Sums the money object amounts in the specified currency.
     *
     * @param array  $money
     * @param string $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function sum(array $money, string $targetCurrency = "EUR"): MoneyInterface;
}