#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use MoveElevator\Sentry\LogPusher\Command\PushCommand;

$application = new Application();
$application->add(new PushCommand());
$application->run();
