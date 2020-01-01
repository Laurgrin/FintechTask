<?php

namespace Source\Parser\Output;

interface OutputInterface
{
    /**
     * Adds a line to the output
     *
     * @param string $line
     */
    public function addLine(string $line): void;
    
    /**
     * Return the output as a formatted string.
     *
     * @return string
     */
    public function getOutput(): string;
}