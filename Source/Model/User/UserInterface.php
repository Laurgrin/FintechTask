<?php

namespace Source\Model\User;

use Source\Model\Operation\OperationInterface;

interface UserInterface
{
    const USER_TYPE_NATURAL = "natural";
    const USER_TYPE_LEGAL   = "legal";
    
    /**
     * Get user ID.
     *
     * @return string
     */
    public function getUserId(): string;
    
    /**
     * Set user ID.
     *
     * @param string $userId
     *
     * @return \Source\Model\User\UserInterface
     */
    public function setUserId(string $userId): UserInterface;
    
    /**
     * Get user type
     *
     * @return string
     */
    public function getUserType(): string;
    
    /**
     * Set user type
     *
     * @param string $type
     *
     * @return \Source\Model\User\UserInterface
     */
    public function setUserType(string $type): UserInterface;
    
    /**
     * Add an operation to the user object. Returns the index of that operation.
     *
     * @param \Source\Model\Operation\OperationInterface $operation
     *
     * @return int
     */
    public function addOperation(OperationInterface $operation): int;
    
    /**
     * Calculates and returns the commission of specified operation. By default, it will be the last one.
     *
     * @param int $operationIndex
     *
     * @return \Source\Model\Operation\OperationInterface
     */
    public function getCommissionAmount(int $operationIndex = -1): OperationInterface;
}