<?php declare(strict_types = 1);

namespace Source\Parser\Output;

class Output implements OutputInterface
{
    /**
     * @var string
     */
    protected $output;
    
    public function __construct()
    {
        $this->output = '';
    }
    
    /**
     * Adds a line to the output
     *
     * @param string $line
     */
    public function addLine(string $line)
    {
        $this->output .= $line . PHP_EOL;
    }
    
    protected function clearOutput()
    {
        $this->output = '';
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