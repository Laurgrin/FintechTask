<?php

namespace Source\Model\Currency;

interface CurrencyInterface
{
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
     * @return mixed
     */
    public function setAmount(string $amount);
    
    /**
     * Returns the currency type.
     *
     * @return string
     */
    public function getType(): string;
    
    /**
     * Sets the currency type.
     *
     * @param string $type
     *
     * @return \Source\Model\Currency\CurrencyInterface
     */
    public function setType(string $type): CurrencyInterface;
}