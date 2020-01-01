<?php

namespace Source\Parser;

use Source\Parser\Output\OutputInterface;

abstract class AbstractParser implements OperationParserInterface
{
    /**
     * @var \Source\Parser\Output\OutputInterface
     */
    protected $output;
    
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }
}