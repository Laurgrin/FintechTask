<?php declare(strict_types = 1);

namespace Source\Parser\Calculator;

use Source\Model\Money\MoneyInterface;
use Source\Model\Operation\OperationInterface;
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
     * @param array $operations
     * @param int   $operationIndex
     *
     * @return OperationInterface[]
     */
    public function getOperationsInSameWeek(array $operations, int $operationIndex = -1): array
    {
        $result = [];
    
        /* Get the week number of the specified operation*/
        $week = $operationIndex === -1 ? end($operations)->getWeekNumber() :
                $operations[$operationIndex]->getWeekNumber();
    
        foreach ($operations as $operation) {
            if ($week === $operation->getWeekNumber() && $operation->getType() === OperationInterface::OPERATION_TYPE_OUT) {
                $result[] = $operation;
            }
        }
    
        return $result;
    }
    
    /**
     * Sums the money object amounts in the specified currency.
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
}