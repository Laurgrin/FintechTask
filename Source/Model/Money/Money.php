<?php declare(strict_types = 1);

namespace Source\Model\Money;

class Money implements MoneyInterface
{
    /**
     * @var string
     */
    protected $amount;
    
    /**
     * @var string
     */
    protected $currencyName;
    
    /**
     * Returns the amount of currency.
     *
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }
    
    /**
     * Sets the amount of currency
     *
     * @param string $amount
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function setAmount(string $amount): MoneyInterface
    {
        $this->amount = $amount;
        
        return $this;
    }
    
    /**
     * Returns the currency type.
     *
     * @return string
     */
    public function getCurrencyName(): string
    {
        return $this->currencyName;
    }
    
    /**
     * Sets the currency type.
     *
     * @param string $name
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function setCurrencyName(string $name): MoneyInterface
    {
        $this->currencyName = $name;
        
        return $this;
    }
}