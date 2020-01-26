<?php declare(strict_types = 1);

namespace Source\Parser\Calculator;

use Source\Exception\FileNotFoundException;
use Source\Model\Money\MoneyInterface;

class CurrencyConverter
{
    const CONVERSION = 'conversion';
    const PRECISION  = 'precision';
    
    protected $currencyData = [];
    
    /**
     * Returns the parsed conversion rates. Works on the assumption that only EUR rates are defined.
     *
     * @return array
     * @throws \Source\Exception\FileNotFoundException
     */
    public function getCurrencyData(): array
    {
        if (empty($this->currencyData)) {
            if (!file_exists(CURRENCY_PATH)) {
                throw new FileNotFoundException('Currency conversion file not found in ' . CURRENCY_PATH);
            }
            
            $this->currencyData = json_decode(file_get_contents(CURRENCY_PATH), true);
            
            /* Set up conversion rates in reserves - TO EUR*/
            if (array_key_exists(MoneyInterface::CURRENCY_EURO, $this->currencyData) &&
                array_key_exists(self::CONVERSION, $this->currencyData[MoneyInterface::CURRENCY_EURO])
            ) {
                foreach ($this->currencyData[MoneyInterface::CURRENCY_EURO][self::CONVERSION] as $currency => $conversionRate) {
                    $this->currencyData[$currency][self::CONVERSION][MoneyInterface::CURRENCY_EURO]
                        = bcdiv('1', $conversionRate, 4);
                }
            }
        }
        
        return $this->currencyData;
    }
    
    /**
     * @param \Source\Model\Money\MoneyInterface $money
     * @param string                             $targetCurrency
     *
     * @return \Source\Model\Money\MoneyInterface
     * @throws \Source\Exception\FileNotFoundException
     */
    public function convert(MoneyInterface $money, string $targetCurrency = MoneyInterface::CURRENCY_EURO): MoneyInterface
    {
        /* No point in converting if it's to the same currency */
        if ($money->getCurrencyName() === $targetCurrency) {
            return $money;
        }
        
        $currencyData = $this->getCurrencyData();
        $currencyFrom = $money->getCurrencyName();
        
        if (array_key_exists($money->getCurrencyName(), $currencyData) &&
            array_key_exists($targetCurrency, $currencyData[$currencyFrom][self::CONVERSION])
        ) {
            $conversionRate = $currencyData[$currencyFrom][self::CONVERSION][$targetCurrency];
            $money->setCurrencyName($targetCurrency)
                  ->setAmount(bcmul($money->getAmount(), $conversionRate));
        }
        
        return $money;
    }
}