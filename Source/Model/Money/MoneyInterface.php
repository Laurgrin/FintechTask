<?php

namespace Source\Model\Money;

interface MoneyInterface
{
    const CASH_IN_FEE_PERCENTAGE = 0.0003; //0.03%
    const CASH_IN_FEE_MAX        = 5; //Of operation's currency
    
    const CASH_OUT_FREE_WEEKLY_AMOUNT   = 1000;
    const CASH_OUT_FEE                  = 0.003; //0.3%
    const CASH_OUT_FEE_MIN_LEGAL_PERSON = 0.50;
    
    const CURRENCY_EURO      = "EUR";
    const CURRENCY_US_DOLLAR = "USD";
    const CURRENCY_YEN       = "JPY";
    
    /**
     * Returns the amount of currency.
     *
     * @return string
     */
    public function getAmount(): string;
    
    /**
     * Sets the amount of currency
     *
     * @param string $amount
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function setAmount(string $amount): MoneyInterface;
    
    /**
     * Returns the currency name.
     *
     * @return string
     */
    public function getCurrencyName(): string;
    
    /**
     * Sets the currency name.
     *
     * @param string $name
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function setCurrencyName(string $name): MoneyInterface;
    
    /**
     * Gets the operation's commission amount.
     *
     * @return string
     */
    public function getCommissionAmount(): string;
    
    /**
     * Sets the operation's commission amount.
     *
     * @param string $amount
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function setCommissionAmount(string $amount): MoneyInterface;
}