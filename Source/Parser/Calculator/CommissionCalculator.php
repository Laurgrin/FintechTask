<?php declare(strict_types = 1);

namespace Source\Parser\Calculator;

use Source\Exception\OperationTypeException;
use Source\Exception\UserTypeException;
use Source\Model\Money\MoneyInterface;
use Source\Model\Operation\OperationInterface;
use Source\Model\User\UserInterface;

class CommissionCalculator
{
    /**
     * @var \Source\Parser\Calculator\OperationManager
     */
    protected $operationManager;
    
    /**
     * @var \Source\Parser\Calculator\MathInterface
     */
    protected $math;
    
    /**
     * CommissionCalculator constructor.
     *
     * @param \Source\Parser\Calculator\OperationManager $operationManager
     * @param \Source\Parser\Calculator\MathInterface    $math
     */
    public function __construct(OperationManager $operationManager, MathInterface $math)
    {
        $this->operationManager = $operationManager;
        $this->math             = $math;
    }
    
    /**
     * Calculates and returns the commission amount
     *
     * @param \Source\Model\Operation\OperationInterface[] $operations
     * @param string                                       $userType
     * @param int                                          $operationIndex
     *
     * @return \Source\Model\Money\MoneyInterface
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws \Source\Exception\ContainerException
     * @throws \Source\Exception\FileNotFoundException
     * @throws \Source\Exception\OperationTypeException
     * @throws \Source\Exception\UserTypeException
     */
    public function getCommissionAmount(array $operations, string $userType, int $operationIndex = -1): MoneyInterface
    {
        $operation = $operationIndex === -1 ? end($operations) : $operations[$operationIndex];
        
        switch ($operation->getType()) {
            case OperationInterface::OPERATION_TYPE_IN:
                $min              =
                    $this->math->multiply($operation->getMoney(), MoneyInterface::CASH_IN_FEE_PERCENTAGE)
                               ->getAmount();
                $commissionAmount = max($min, MoneyInterface::CASH_IN_FEE_MAX);
                
                return $operation->getMoney()->setCommissionAmount($commissionAmount);
            case OperationInterface::OPERATION_TYPE_OUT:
                switch ($userType) {
                    case UserInterface::USER_TYPE_NATURAL:
                        $operations          = $this->operationManager->getOperationsInSameWeek(
                            $operations,
                            $operationIndex
                        );
                        $operationSum        = $this->operationManager->sumCashOutOperations($operations);
                        $amountAfterDiscount = $this->operationManager->getAmountAfterDiscount(
                            $operations,
                            $operationSum,
                            $operation->getMoney()
                        );
                        $commissionAmount    = $this->math->multiply($amountAfterDiscount, MoneyInterface::CASH_OUT_FEE);
                        return $commissionAmount;
                    case UserInterface::USER_TYPE_LEGAL:
                        break;
                    default:
                        throw new UserTypeException(sprintf('Unsupported user type: %s', $userType));
                }
                break;
            default:
                throw new OperationTypeException(sprintf('Unsupported operation: %s', $operation->getType()));
        }
    }
}