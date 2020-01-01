<?php

namespace Source\Model\User;

use Source\Model\Operation\OperationInterface;

class User implements UserInterface
{
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
        // TODO: Implement getUserId() method.
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
        // TODO: Implement setUserId() method.
    }
    
    /**
     * Get user type
     *
     * @return string
     */
    public function getUserType(): string
    {
        // TODO: Implement getUserType() method.
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
        // TODO: Implement setUserType() method.
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
        // TODO: Implement addOperation() method.
    }
    
    /**
     * Calculates and returns the commission of specified operation. By default, it will be the last one.
     *
     * @param int $operationIndex
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function getCommissionAmount(int $operationIndex = -1): OperationInterface
    {
        // TODO: Implement getCommissionAmount() method.
    }
}