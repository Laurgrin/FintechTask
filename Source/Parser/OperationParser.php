<?php declare(strict_types = 1);

namespace Source\Parser;

use Source\Exception\FileNotFoundException;
use Source\Model\Money\MoneyInterface;
use Source\Model\Operation\OperationInterface;
use Source\Model\User\UserInterface;
use Source\ObjectManager;
use Source\Parser\Output\OutputInterface;
use SplFileObject;

class OperationParser extends AbstractParser
{
    
    /**
     * Parses the input file and returns the output. Should be considered as an entry point.
     *
     * @param string $inputFile
     *
     * @return \Source\Parser\Output\OutputInterface
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws \Source\Exception\ContainerException
     * @throws \Source\Exception\FileNotFoundException
     * @throws \Source\Exception\OperationTypeException
     * @throws \Source\Exception\UserTypeException
     */
    public function parseOperations(string $inputFile): OutputInterface
    {
        $fileHandle = $this->getInputHandle($inputFile);
        
        /** @var \Source\Model\User\UserInterface[] $users */
        $users      = [];
        while (!$fileHandle->eof()) {
            $line = $this->parseLine($fileHandle->fgetcsv());
            $this->addOperation($users, $line);
            
            $user = $users[$line['user_id']];
            $commissionAmount = $this->commissionCalculator->getCommissionAmount($user->getOperations(), $user->getUserType());
            var_dump($commissionAmount);
            die();
        }
    }
    
    /**
     * Adds an operation to the user array using the CSV line.
     *
     * @param array $users
     * @param array $line
     *
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws \Source\Exception\ContainerException
     * @throws \Source\Exception\FileNotFoundException
     */
    protected function addOperation(array &$users, array $line)
    {
        $objectManager = ObjectManager::getInstance();
        
        /* If there's no such user yet, create and initialize it. Skip creation otherwise. */
        if (!array_key_exists($line['user_id'], $users)) {
            $users[$line['user_id']] = $objectManager->get(UserInterface::class);
            $users[$line['user_id']]->setUserId($line['user_id'])->setUserType($line['user_type']);
        }
    
        /* Create money object to add to the operation object */
        /** @var \Source\Model\Money\MoneyInterface $money */
        $money = $objectManager->get(MoneyInterface::class);
        $money->setAmount($line['operation_amount'])->setCurrencyName($line['operation_currency']);
    
        /** @var \Source\Model\Operation\OperationInterface $operation */
        $operation = $objectManager->get(OperationInterface::class);
        $operation->setDate($line['date'])->setMoney($money)->setType($line['operation_type']);
    
        $users[$line['user_id']]->addOperation($operation);
    }
    
    /**
     * Parse a csv line array into an assoc array.
     *
     * @param array $line
     *
     * @return array
     */
    protected function parseLine(array $line): array
    {
        return [
            'date'               => $line[0],
            'user_id'            => $line[1],
            'user_type'          => $line[2],
            'operation_type'     => $line[3],
            'operation_amount'   => $line[4],
            'operation_currency' => $line[5],
        ];
    }
    
    /**
     * @param string $inputFile
     *
     * @return \SplFileObject
     * @throws \Source\Exception\FileNotFoundException
     */
    protected function getInputHandle(string $inputFile): SplFileObject
    {
        if (!file_exists($inputFile)) {
            throw new FileNotFoundException("Input file $inputFile not found.");
        }
        
        $fileHandle = new SplFileObject($inputFile);
        $fileHandle->setCsvControl(',', '"');
        
        return $fileHandle;
    }
}