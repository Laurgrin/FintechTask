<?php

namespace Source\Model\Money;

interface MoneyInterface
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
     * @return \Source\Model\Money\MoneyInterface
     */
    public function setAmount(string $amount): MoneyInterface;
    
    /**
     * Returns the currency type.
     *
     * @return string
     */
    public function getCurrency(): string;
    
    /**
     * Sets the currency type.
     *
     * @param string $type
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function setCurrency(string $type): MoneyInterface;
}