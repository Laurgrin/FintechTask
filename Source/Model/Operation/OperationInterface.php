<?php

namespace Source\Model\Operation;

use Source\Model\Currency\CurrencyInterface;

interface OperationInterface
{
    const OPERATION_TYPE_IN  = "cash_in";
    const OPERATION_TYPE_OUT = "cast_out";
    const DATE_FORMAT        = "Y-m-d";
    
    /**
     * Get operation's date.
     *
     * @return string
     */
    public function getDate(): string;
    
    /**
     * Set operation's date.
     *
     * @param string $date
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setDate(string $date): OperationInterface;
    
    /**
     * Set operation's type.
     *
     * @return string
     */
    public function getType(): string;
    
    /**
     * Get operation's type.
     *
     * @param string $type
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setType(string $type): OperationInterface;
    
    /**
     * Get operation's currency.
     *
     * @return \Source\Model\Currency\CurrencyInterface
     */
    public function getCurrency(): CurrencyInterface;
    
    /**
     * Set operation's currency
     *
     * @param \Source\Model\Currency\CurrencyInterface $currency
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setCurrency(CurrencyInterface $currency): OperationInterface;
    
    /**
     * Gets the operation's commission amount.
     *
     * @return \Source\Model\Currency\CurrencyInterface
     */
    public function getCommissionAmount(): CurrencyInterface;
    
    /**
     * Sets the operation's commission amount.
     *
     * @param \Source\Model\Currency\CurrencyInterface $currency
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setCommissionAmount(CurrencyInterface $currency): OperationInterface;
}