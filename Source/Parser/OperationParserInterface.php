<?php declare(strict_types = 1);

namespace Source\Parser;

use Source\Parser\Output\OutputInterface;

interface OperationParserInterface
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
    public function parseOperations(string $inputFile): OutputInterface;
}