<?php

namespace Source\Model\Operation;

use Source\Model\Money\MoneyInterface;

class Operation implements OperationInterface
{
    /**
     * @var \DateTime
     */
    protected $date;
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var MoneyInterface
     */
    protected $amount;
    
    /**
     * @var MoneyInterface
     */
    protected $cashOutDiscount;
    
    /**
     * Operation constructor.
     *
     * @param \Source\Model\Money\MoneyInterface $cashOutDiscount
     */
    public function __construct(MoneyInterface $cashOutDiscount)
    {
        $cashOutDiscount->setAmount("1000")->setCurrencyName("EUR");
        $this->cashOutDiscount = $cashOutDiscount;
    }
    
    /**
     * Get operation's date.
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->date->format(self::DATE_FORMAT);
    }
    
    /**
     * Set operation's date.
     *
     * @param string $date
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setDate(string $date): OperationInterface
    {
        $this->date = \DateTime::createFromFormat(self::DATE_FORMAT, $date);
        
        return $this;
    }
    
    /**
     * Set operation's type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    
    /**
     * Get operation's type.
     *
     * @param string $type
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setType(string $type): OperationInterface
    {
        $this->type = $type;
        
        return $this;
    }
    
    /**
     * Get operation's currency.
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function getMoney(): MoneyInterface
    {
        return $this->amount;
    }
    
    /**
     * Set operation's currency
     *
     * @param \Source\Model\Money\MoneyInterface $money
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setMoney(MoneyInterface $money): OperationInterface
    {
        $this->amount = $money;
        
        return $this;
    }
    
    /**
     * Returns the number of the week in year of the operation (set by setDate).
     *
     * @return int
     */
    public function getWeekNumber(): int
    {
        return $this->date->format("W");
    }
    
    /**
     * Get the operation's remaining weekly cash out discount.
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function getWeeklyCashOutDiscount(): MoneyInterface
    {
        return $this->cashOutDiscount;
    }
    
    /**
     * Set the operation's remaining weekly cash out discount.
     *
     * @param \Source\Model\Money\MoneyInterface $money
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function setWeeklyCashOutDiscount(MoneyInterface $money): OperationInterface
    {
        $this->cashOutDiscount = $money;
        
        return $this;
    }
}