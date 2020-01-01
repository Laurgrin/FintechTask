<?php

namespace Source\Model\Operation;

use Source\Model\Money\MoneyInterface;

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
     * @return \Source\Model\Money\MoneyInterface
     */
    public function getMoney(): MoneyInterface;
    
    /**
     * Set operation's currency
     *
     * @param \Source\Model\Money\MoneyInterface $money
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setMoney(MoneyInterface $money): OperationInterface;
    
    /**
     * Gets the operation's commission amount.
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function getCommissionAmount(): MoneyInterface;
    
    /**
     * Sets the operation's commission amount.
     *
     * @param \Source\Model\Money\MoneyInterface $money
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setCommissionAmount(MoneyInterface $money): OperationInterface;
}