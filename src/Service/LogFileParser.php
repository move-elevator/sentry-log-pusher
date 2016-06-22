<?php

namespace MoveElevator\Sentry\LogPusher\Service;

use MoveElevator\Sentry\LogPusher\Exception\LogFileException;
use TM\ErrorLogParser\Parser;

/**
 * @package MoveElevator\Sentry\LogPusher\Service
 */
class LogFileParser
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var array
     */
    private $entries = [];

    /**
     * @var int
     */
    private $count = 0;

    /**
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param string      $file
     * @param string|null $types
     *
     * @throws LogFileException
     */
    public function parse($file, $types = null)
    {
        $this->entries = [];
        $this->count = 0;

        foreach ($this->getLines($file) as $line) {
            $entry = $this->parser->parse($line);

            if (false === empty($types) && false === in_array($entry->type, $types)) {
                continue;
            }

            $this->entries[] = $entry;
            $this->count++;
        }
    }

    /**
     * @return array
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * @param string $file
     *
     * @return \Generator
     * @throws LogFileException
     */
    private function getLines($file)
    {
        $resource = fopen($file, 'r');

        if (false === $resource) {
            throw new LogFileException(sprintf('The log file "%s" could not be opened.', $file));
        }

        while ($line = fgets($resource)) {
            yield $line;
        }

        fclose($resource);
    }
}
