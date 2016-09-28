<?php

namespace MoveElevator\Sentry\LogPusher\Tests\Service;

use MoveElevator\Sentry\LogPusher\Service\LogFileParser;
use TM\ErrorLogParser\Parser;

/**
 * @package MoveElevator\Sentry\LogPusher\Tests\Service
 */
class LogFileParserTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleAllFormlessLogTypes()
    {
        $logFileParser = new LogFileParser(new Parser(Parser::TYPE_FORMLESS));
        $logFileParser->parse(__DIR__ . '/../DataFixtures/logs/apache.log');

        $this->assertEquals(2, $logFileParser->count());
    }

    public function testHandleAllLogTypes()
    {
        $logFileParser = new LogFileParser(new Parser(Parser::TYPE_APACHE));
        $logFileParser->parse(__DIR__ . '/../DataFixtures/logs/apache.log');

        $this->assertEquals(2, $logFileParser->count());
    }

    public function testHandleWarningLogType()
    {
        $logFileParser = new LogFileParser(new Parser(Parser::TYPE_APACHE));
        $logFileParser->parse(__DIR__ . '/../DataFixtures/logs/apache.log', ['warn']);

        $this->assertEquals(1, $logFileParser->count());

        $entry = $logFileParser->getEntries()[0];
        $this->assertEquals('warn', $entry->type);
    }
}
