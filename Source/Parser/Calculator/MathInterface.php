<?php declare(strict_types = 1);

namespace Source\Parser\Calculator;

use Source\Model\Money\MoneyInterface;

interface MathInterface
{
    /**
     * Multiplies a money value
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $amount
     *
     * @param string                             $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function multiply(
        MoneyInterface $money,
        string $amount,
        string $targetCurrency = MoneyInterface::CURRENCY_EURO
    ): MoneyInterface;
    
    /**
     * Sums a money value.
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $amount
     *
     * @param string                             $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function add(
        MoneyInterface $money,
        string $amount,
        string $targetCurrency = MoneyInterface::CURRENCY_EURO
    ): MoneyInterface;
    
    /**
     * Subtracts the specified sum from the money object
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $amount
     *
     * @param string                             $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function subtract(
        MoneyInterface $money,
        string $amount,
        string $targetCurrency = MoneyInterface::CURRENCY_EURO
    ): MoneyInterface;
    
    /**
     * Convert one currency to another
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function convert(MoneyInterface $money, string $targetCurrency = MoneyInterface::CURRENCY_EURO): MoneyInterface;
}