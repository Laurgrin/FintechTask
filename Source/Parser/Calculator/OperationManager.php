<?php declare(strict_types = 1);

namespace Source\Parser\Calculator;

use Source\Model\Money\MoneyInterface;
use Source\Model\Operation\OperationInterface;
use Source\Model\User\UserInterface;
use Source\ObjectManager;

class OperationManager
{
    /**
     * @var \Source\Parser\Calculator\MathInterface
     */
    protected $math;
    
    /**
     * OperationManager constructor.
     *
     * @param \Source\Parser\Calculator\MathInterface $math
     */
    public function __construct(MathInterface $math) {
        $this->math = $math;
    }
    
    /**
     * Returns the operations made in the same week as the one specified by the $operationIndex. If it is not specified,
     * it will take the last operation by default.
     *
     * @param array $operations
     * @param int   $operationIndex
     *
     * @return OperationInterface[]
     */
    public function getOperationsInSameWeek(array $operations, int $operationIndex = -1): array
    {
        $result = [];
    
        /* Get the week number of the specified operation*/
        if ($operationIndex === -1) {
            $week = end($operations)->getWeekNumber();
            /* We shouldn't count the current operation, we just needed it's week */
            array_pop($operations);
        } else {
            $week = $operations[$operationIndex]->getWeekNumber();
            /* Remove all elements after index, we don't need future operations */
            $operations = array_slice($operations, 0, count($operations) - ($operationIndex + 1));
        }
        
        foreach ($operations as $operation) {
            if ($week === $operation->getWeekNumber() && $operation->getType() === OperationInterface::OPERATION_TYPE_OUT) {
                $result[] = $operation;
            }
        }
    
        return $result;
    }
    
    /**
     * Sums the cash out operations
     *
     * @param \Source\Model\Operation\OperationInterface[] $operations
     *
     * @return \Source\Model\Money\MoneyInterface
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws \Source\Exception\ContainerException
     * @throws \Source\Exception\FileNotFoundException
     */
    public function sumCashOutOperations(array $operations): MoneyInterface
    {
        $objectManager = ObjectManager::getInstance();
        
        /** @var MoneyInterface $sum */
        $sum = $objectManager->get(MoneyInterface::class);
        $sum->setCurrencyName(MoneyInterface::CURRENCY_EURO)->setAmount('0');
        
        foreach ($operations as $operation) {
            $operationAmount = $operation->getMoney();
            $sum = $this->math->add($sum, $operationAmount->getAmount());
        }
        
        return $sum;
    }
    
    /**
     * Returns the amount from which to calculate commission from.
     *
     * @param array                              $operations
     * @param \Source\Model\Money\MoneyInterface $sum
     * @param \Source\Model\Money\MoneyInterface $amount
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function getAmountAfterDiscount(array $operations, MoneyInterface $sum, MoneyInterface $amount): MoneyInterface
    {
        /* If we hit the weekly discounted operation limit, it no longer applies */
        if (count($operations) > OperationInterface::DISCOUNT_OPERATION_WEEKLY_LIMIT) {
            return $sum;
        }
        
        $remainingDiscount = $this->math->subtract($sum, OperationInterface::DISCOUNT_AMOUNT_WEEKLY_LIMIT);
        $remainingDiscount->setAmount((string)abs($remainingDiscount->getAmount()));
        if ((float)$remainingDiscount->getAmount() > 0) {
            $amountAfterDiscount = $this->math->subtract($amount, $remainingDiscount->getAmount());
            
            /* We should really return negative numbers */
            return $amountAfterDiscount > 0 ? $amountAfterDiscount : $amountAfterDiscount->setAmount('0');
        }
        
        return $amount;
    }
}