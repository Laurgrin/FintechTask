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
        $return       = clone($money);
        $precision    = $currencyData[$targetCurrency][CurrencyConverter::PRECISION];
        
        if ($return->getCurrencyName() !== $targetCurrency) {
            $this->convert($return, $targetCurrency);
        }
        
        $result = bcmul($return->getAmount(), $multiplier, $precision + 1);
        $result = (string)round($result, $precision);
        
        return $return->setAmount($result);
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
        $return       = clone($money);
        $precision    = $currencyData[$targetCurrency][CurrencyConverter::PRECISION];
        
        if ($return->getCurrencyName() !== $targetCurrency) {
            $this->convert($return, $targetCurrency);
        }
        
        $result = bcsub($return->getAmount(), $amount, $precision + 1);
        $result = (string)round($result, $precision);
        
        return $return->setAmount($result);
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
        $return       = clone($money);
        $precision    = $currencyData[$targetCurrency][CurrencyConverter::PRECISION];
        
        if ($return->getCurrencyName() !== $targetCurrency) {
            $this->convert($return, $targetCurrency);
        }
        
        $result = bcadd($return->getAmount(), $amount, $precision + 1);
        $result = (string)round($result, $precision);
        
        return $return->setAmount($result);
    }
    
    /**
     * Convert one currency to another
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     * @throws \Source\Exception\FileNotFoundException
     */
    public function convert(
        MoneyInterface $money,
        string $targetCurrency = MoneyInterface::CURRENCY_EURO
    ): MoneyInterface {
        return $this->currencyConverter->convert($money, $targetCurrency);
    }
}