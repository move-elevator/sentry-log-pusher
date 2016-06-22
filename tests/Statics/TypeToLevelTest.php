<?php

namespace MoveElevator\Sentry\LogPusher\Tests\Statics;

use MoveElevator\Sentry\LogPusher\Statics\TypeToLevel;

/**
 * @package MoveElevator\Sentry\LogPusher\Tests\Statics
 */
class TypeToLevelTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingOfTypeToLevel()
    {
        $typeToLevel = new TypeToLevel();

        $this->assertEquals(\Raven_Client::ERROR, $typeToLevel->getLevel('error'));
        $this->assertEquals(\Raven_Client::WARNING, $typeToLevel->getLevel('warn'));
        $this->assertEquals(\Raven_Client::INFO, $typeToLevel->getLevel('nice'));
    }
}
