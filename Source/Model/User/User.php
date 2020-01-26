<?php declare(strict_types = 1);

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
     * Returns all operations for this user.
     *
     * @return OperationInterface[]
     */
    public function getOperations(): array
    {
        return $this->operations;
    }
}