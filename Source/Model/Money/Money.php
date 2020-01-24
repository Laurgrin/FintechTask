<?php declare(strict_types = 1);

namespace Source\Model\Money;

use Source\Exception\FileNotFoundException;

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
     * @var string
     */
    protected $commission;
    
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
    
    /**
     * Gets the operation's commission amount.
     *
     * @return string
     */
    public function getCommissionAmount(): string
    {
        return $this->commission;
    }
    
    /**
     * Sets the operation's commission amount.
     *
     * @param string $amount
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function setCommissionAmount(string $amount): MoneyInterface
    {
        $this->commission = $amount;
        
        return $this;
    }
}