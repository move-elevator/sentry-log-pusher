<?php

namespace MoveElevator\Sentry\LogPusher\Command;

use MoveElevator\Sentry\LogPusher\Service\LogFileParser;
use MoveElevator\Sentry\LogPusher\Service\SentryPusher;
use MoveElevator\Sentry\LogPusher\Statics\TypeToLevel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;
use TM\ErrorLogParser\Parser;

/**
 * @package MoveElevator\Sentry\LogPusher\Command
 */
class PushCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('push')
            ->setDescription('parse the logfile and push it to sentry')
            ->addArgument('logfile', InputArgument::REQUIRED, 'path to the error log')
            ->addOption('logfile-type', 's', InputOption::VALUE_OPTIONAL, 'type of error log: apache or nginx', Parser::TYPE_APACHE)
            ->addOption('log-type', 't', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'which log types should be pushed: error, warn, ...')
            ->addOption('sentry-dsn', 'd', InputOption::VALUE_REQUIRED, 'sentry dsn')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Parse the logfile and push it to sentry');

        $logFileParser = new LogFileParser(new Parser($input->getOption('logfile-type')));
        $logFileParser->parse($input->getArgument('logfile'), $input->getOption('log-type'));

        $sentryPusher = new SentryPusher(new \Raven_Client($input->getOption('sentry-dsn')));
        $typeToLevel = new TypeToLevel();

        $progress = new ProgressBar($output, $logFileParser->count());
        $progress->start();

        if (Parser::TYPE_FORMLESS === $input->getOption('logfile-type')) {
            $sentryPusher->pushMultiline($logFileParser->getEntries(), $typeToLevel);
        }

        if (Parser::TYPE_FORMLESS !== $input->getOption('logfile-type')) {
            foreach ($logFileParser->getEntries() as $entry) {
                $sentryPusher->push($entry, $typeToLevel);
                $progress->advance();
            }
        }

        $progress->finish();

        $io->newLine(2);
        $io->success('Done.');
    }
}
