<?php declare(strict_types = 1);

namespace Source\Parser;

use Source\Parser\Calculator\CommissionCalculator;
use Source\Parser\Output\OutputInterface;

abstract class AbstractParser implements OperationParserInterface
{
    /**
     * @var \Source\Parser\Output\OutputInterface
     */
    protected $output;
    
    /**
     * @var \Source\Parser\Calculator\CommissionCalculator
     */
    protected $commissionCalculator;
    
    /**
     * AbstractParser constructor.
     *
     * @param \Source\Parser\Output\OutputInterface          $output
     * @param \Source\Parser\Calculator\CommissionCalculator $commissionCalculator
     */
    public function __construct(OutputInterface $output, CommissionCalculator $commissionCalculator)
    {
        $this->output               = $output;
        $this->commissionCalculator = $commissionCalculator;
    }
}