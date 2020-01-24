<?php declare(strict_types = 1);

namespace Source\Model\Operation;

use Source\Model\Money\MoneyInterface;

interface OperationInterface
{
    const OPERATION_TYPE_IN  = 'cash_in';
    const OPERATION_TYPE_OUT = 'cash_out';
    const DATE_FORMAT        = 'Y-m-d';
    
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
     * Get operation's money object.
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function getMoney(): MoneyInterface;
    
    /**
     * Set operation's money object
     *
     * @param \Source\Model\Money\MoneyInterface $money
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setMoney(MoneyInterface $money): OperationInterface;
    
    /**
     * Get the operation's remaining weekly cash out discount.
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function getWeeklyCashOutDiscount(): MoneyInterface;
    
    /**
     * Set the operation's remaining weekly cash out discount.
     *
     * @param \Source\Model\Money\MoneyInterface $money
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setWeeklyCashOutDiscount(MoneyInterface $money): OperationInterface;
    
    /**
     * Returns the number of the week in year of the operation (set by setDate).
     *
     * @return int
     */
    public function getWeekNumber(): int;
}