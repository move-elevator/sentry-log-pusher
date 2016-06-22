<?php

namespace MoveElevator\Sentry\LogPusher\Tests\Command;

use MoveElevator\Sentry\LogPusher\Command\PushCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * This test is for debugging. Add your sentry dsn to the const and test the command
 *
 * @package MoveElevator\Sentry\LogPusher\Tests\Command
 */
class PushCommandTest extends \PHPUnit_Framework_TestCase
{
    const SENTRY_DSN = '';

    public function testNameIsOutput()
    {
        if (true == empty(self::SENTRY_DSN)) {
            $this->markTestIncomplete('Add your sentry dsn to test and debug the push command.');
        }

        $application = new Application();
        $application->add(new PushCommand());

        $command = $application->find('push');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'logfile' => __DIR__ . '/../DataFixtures/logs/apache.log',
            '--sentry-dsn' => self::SENTRY_DSN,
        ]);

        $this->assertContains('Done', $commandTester->getDisplay());
    }
}
