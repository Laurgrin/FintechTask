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
        $result    = clone($operation->getMoney());
        
        switch ($operation->getType()) {
            case OperationInterface::OPERATION_TYPE_IN:
                $min              =
                    $this->math->multiply($operation->getMoney(),
                                          MoneyInterface::CASH_IN_FEE_PERCENTAGE,
                                          $operation->getMoney()->getCurrencyName()
                    )->getAmount();
                $commissionAmount = min($min, MoneyInterface::CASH_IN_FEE_MAX);
                
                $result->setAmount($commissionAmount);
                
                return $result;
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
                        $commissionAmount    = $this->math->multiply(
                            $amountAfterDiscount,
                            MoneyInterface::CASH_OUT_FEE,
                            $operation->getMoney()->getCurrencyName()
                        );
                        
                        $result->setAmount($commissionAmount->getAmount());
                        
                        return $result;
                    case UserInterface::USER_TYPE_LEGAL:
                        $legalPersonCommission =
                            $this->math->multiply(
                                $operation->getMoney(),
                                MoneyInterface::CASH_OUT_FEE,
                                $operation->getMoney()->getCurrencyName()
                            );
                        $commissionAmount      =
                            max(MoneyInterface::CASH_OUT_FEE_MIN_LEGAL_PERSON, $legalPersonCommission->getAmount());
                        
                        $result->setAmount($commissionAmount);
                        
                        return $result;
                    default:
                        throw new UserTypeException(sprintf('Unsupported user type: %s', $userType));
                }
            default:
                throw new OperationTypeException(sprintf('Unsupported operation: %s', $operation->getType()));
        }
    }
}