<?php declare(strict_types = 1);

namespace Source\Parser\Output;

interface OutputInterface
{
    /**
     * Adds a line to the output
     *
     * @param string $line
     */
    public function addLine(string $line);
    
    /**
     * Return the output as a formatted string.
     *
     * @return string
     */
    public function getOutput(): string;
}