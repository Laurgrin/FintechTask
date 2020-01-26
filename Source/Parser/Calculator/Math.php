<?php declare(strict_types = 1);

namespace Source\Parser\Calculator;

use Source\Model\Money\MoneyInterface;

class Math implements MathInterface
{
    /**
     * @var \Source\Parser\Calculator\CurrencyConverter
     */
    protected $currencyConverter;
    
    public function __construct(CurrencyConverter $currencyConverter)
    {
        $this->currencyConverter = $currencyConverter;
    }
    
    /**
     * Multiplies a money value and returns the result object.
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $multiplier
     *
     * @param string                             $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     * @throws \Source\Exception\FileNotFoundException
     */
    public function multiply(
        MoneyInterface $money,
        string $multiplier,
        string $targetCurrency = MoneyInterface::CURRENCY_EURO
    ): MoneyInterface {
        $currencyData = $this->currencyConverter->getCurrencyData();
        if ($money->getCurrencyName() !== $targetCurrency) {
            $this->currencyConverter->convert($money, $targetCurrency);
        }
        
        $result =
            bcmul($money->getAmount(), $multiplier, $currencyData[$money->getCurrencyName()][CurrencyConverter::PRECISION]);
        
        return $money->setAmount($result);
    }
    
    /**
     * Subtracts the specified sum from the money object
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $amount
     *
     * @param string                             $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     * @throws \Source\Exception\FileNotFoundException
     */
    public function subtract(
        MoneyInterface $money,
        string $amount,
        string $targetCurrency = MoneyInterface::CURRENCY_EURO
    ): MoneyInterface {
        $currencyData = $this->currencyConverter->getCurrencyData();
        if ($money->getCurrencyName() !== $targetCurrency) {
            $this->currencyConverter->convert($money, $targetCurrency);
        }
        
        $result =
            bcsub($money->getAmount(), $amount, $currencyData[$money->getCurrencyName()][CurrencyConverter::PRECISION]);
        
        $money->setAmount($result);
        
        return $money;
    }
    
    /**
     * Sums a money value.
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $amount
     *
     * @param string                             $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     * @throws \Source\Exception\FileNotFoundException
     */
    public function add(
        MoneyInterface $money,
        string $amount,
        string $targetCurrency = MoneyInterface::CURRENCY_EURO
    ): MoneyInterface {
        $currencyData = $this->currencyConverter->getCurrencyData();
        if ($money->getCurrencyName() !== $targetCurrency) {
            $this->currencyConverter->convert($money, $targetCurrency);
        }
        
        $result =
            bcadd($money->getAmount(), $amount, $currencyData[$money->getCurrencyName()][CurrencyConverter::PRECISION]);
        
        $money->setAmount($result);
        
        return $money;
    }
}