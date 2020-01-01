<?php

namespace Source\Parser\Output;

class Output implements OutputInterface
{
    /**
     * @var string
     */
    protected $output;
    
    public function __construct()
    {
        $this->output = "";
    }
    
    /**
     * Adds a line to the output
     *
     * @param string $line
     */
    public function addLine(string $line): void
    {
        $this->output .= $line . PHP_EOL;
    }
    
    protected function clearOutput():void
    {
        $this->output = "";
    }
    
    /**
     * Return the output as a formatted string.
     *
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }
}