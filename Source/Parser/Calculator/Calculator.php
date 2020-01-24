<?php declare(strict_types = 1);

namespace Source\Parser\Calculator;

use Source\Exception\FileNotFoundException;
use Source\Model\Money\MoneyInterface;
use Source\ObjectFactory;

class Calculator implements CalculatorInterface
{
    const PRECISION  = 'precision';
    const CONVERSION = 'conversion';
    
    /**
     * @var array
     */
    protected $currencyData;
    
    /**
     * Money constructor.
     * @throws \Source\Exception\FileNotFoundException
     */
    public function __construct()
    {
        if (!file_exists(CURRENCY_PATH)) {
            throw new FileNotFoundException('Currency conversion file not found in ' . CURRENCY_PATH);
        }
        
        $this->currencyData = json_decode(file_get_contents(CURRENCY_PATH), true);
    }
    
    /**
     * Multiplies a money value and returns the result object. Currency precision taken from currency.json
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $multiplier
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function multiply(MoneyInterface $money, string $multiplier): MoneyInterface
    {
        $result =
            bcmul($money->getAmount(), $multiplier, $this->currencyData[$money->getCurrencyName()][self::PRECISION]);
        
        return $money->setAmount($result);
    }
    
    /**
     * Sums the money object amounts in the specified currency.
     *
     * @param MoneyInterface[] $money
     * @param string           $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     * @throws \ReflectionException
     * @throws \Source\Exception\DefinitionNotFoundException
     * @throws \Source\Exception\FileNotFoundException
     */
    public function sumCashOutOperations(array $money): MoneyInterface
    {
        /** @var MoneyInterface $sum */
        $sum = ObjectFactory::build('money');
        $sum->setCurrencyName(MoneyInterface::CURRENCY_EURO)->setAmount('0');
        
        foreach ($money as $operationAmount) {
            $currentSum = $this->toEur($sum)->getAmount();
            $addedSum   = bcadd(
                $currentSum,
                $this->toEur($operationAmount)->getAmount(),
                $this->currencyData[$operationAmount->getCurrencyName()][self::PRECISION]
            );
            $sum->setAmount($addedSum);
        }
        
        return $sum;
    }
    
    /**
     * Converts a money object's currency amount to an equivalent amount in EUR. If it's already EUR, just returns
     * the object back.
     *
     * @param \Source\Model\Money\MoneyInterface $money
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    protected function toEur(MoneyInterface $money): MoneyInterface
    {
        if ($money->getCurrencyName() !== MoneyInterface::CURRENCY_EURO) {
            $money->setCurrencyName(MoneyInterface::CURRENCY_EURO)
                  ->setAmount(
                      bcdiv(
                          $money->getAmount(),
                          $this->currencyData[MoneyInterface::CURRENCY_EURO][self::CONVERSION][$money->getCurrencyName()],
                          $this->currencyData[$money->getCurrencyName()][self::PRECISION]
                      )
                  );
        }
        
        return $money;
    }
    
    /**
     * Subtracts the specified sum from the money object
     *
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $amount
     *
     * @return \Source\Model\Money\MoneyInterface
     */
    public function subtract(MoneyInterface $money, string $amount): MoneyInterface
    {
        // TODO: Implement subtract() method.
    }
}