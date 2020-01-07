<?php

namespace Source\Model\User;

use Source\Exception\OperationTypeException;
use Source\Model\Money\MoneyInterface;
use Source\Model\Operation\OperationInterface;
use Source\Parser\Calculator\CalculatorInterface;

class User implements UserInterface
{
    /**
     * @var \Source\Parser\Calculator\CalculatorInterface
     */
    protected $calculator;
    
    public function __construct(CalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }
    
    /**
     * @var string
     */
    protected $userId;
    
    /**
     * @var string
     */
    protected $userType;
    
    /**
     * @var OperationInterface[]
     */
    protected $operations = [];
    
    /**
     * Get user ID.
     *
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
    
    /**
     * Set user ID.
     *
     * @param string $userId
     *
     * @return \Source\Model\User\UserInterface
     */
    public function setUserId(string $userId): UserInterface
    {
        $this->userId = $userId;
        
        return $this;
    }
    
    /**
     * Get user type
     *
     * @return string
     */
    public function getUserType(): string
    {
        return $this->userType;
    }
    
    /**
     * Set user type
     *
     * @param string $type
     *
     * @return \Source\Model\User\UserInterface
     */
    public function setUserType(string $type): UserInterface
    {
        $this->userType = $type;
        
        return $this;
    }
    
    /**
     * Add an operation to the user object. Returns the index of that operation.
     *
     * @param \Source\Model\Operation\OperationInterface $operation
     *
     * @return int
     */
    public function addOperation(OperationInterface $operation): int
    {
        $this->operations[] = $operation;
        
        return array_key_last($this->operations);
    }
    
    /**
     * Calculates and returns the commission of specified operation. By default, it will be the last one.
     *
     * @param int $operationIndex
     *
     * @return \Source\Model\Operation\OperationInterface
     * @throws \Source\Exception\OperationTypeException
     */
    public function getCommissionAmount(int $operationIndex = -1): string
    {
        $operation = $operationIndex === -1 ? end($this->operations) : $this->operations[$operationIndex];
        
        switch ($operation->getType()) {
            case OperationInterface::OPERATION_TYPE_IN:
                return max(
                    $this->calculator->multiply(
                        $operation->getMoney(),
                        MoneyInterface::CASH_IN_FEE_PERCENTAGE
                    )->getAmount(),
                    MoneyInterface::CASH_IN_FEE_MAX
                );
            case OperationInterface::OPERATION_TYPE_OUT:
                $operations       = $this->getOperationsInSameWeek($operationIndex);
                $operationAmounts = [];
                
                foreach ($operations as $operation) {
                    $operationAmounts[] = $operation->getMoney();
                }
                
                var_dump($this->calculator->sum($operationAmounts));
                die();
            default:
                throw new OperationTypeException("Unsupported operation {$operation->getType()}");
        }
    }
    
    /**
     * Returns an array of cash out operations made in the same week as the operation with the specified index.
     * By default, it will use the week of the latest operation.
     *
     * @param int $operationIndex
     *
     * @return OperationInterface[]
     */
    protected function getOperationsInSameWeek(int $operationIndex = -1): array
    {
        $operations = [];
        
        /* Get the week number of the specified operation*/
        $week =
            $operationIndex === -1 ? end($this->operations)->getWeekNumber() :
                $this->operations[$operationIndex]->getWeekNumber();
        
        foreach ($this->operations as $operation) {
            if ($week === $operation->getWeekNumber() && $operation->getType() === OperationInterface::OPERATION_TYPE_OUT) {
                $operations[] = $operation;
            }
        }
        
        return $operations;
    }
}