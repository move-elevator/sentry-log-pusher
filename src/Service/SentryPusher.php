<?php

namespace MoveElevator\Sentry\LogPusher\Service;

use MoveElevator\Sentry\LogPusher\Exception\SentryException;
use MoveElevator\Sentry\LogPusher\Statics\TypeToLevelInterface;

/**
 * @package MoveElevator\Sentry\LogPusher\Service
 */
class SentryPusher
{
    /**
     * @var \Raven_Client
     */
    private $client;

    /**
     * @param \Raven_Client $client
     */
    public function __construct(\Raven_Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param \stdClass            $entry
     * @param TypeToLevelInterface $typeToLevel
     *
     * @return int
     */
    public function push($entry, TypeToLevelInterface $typeToLevel)
    {
        $options = [
            'tags' => ['Source' => 'Log'],
            'extra' => (array) $entry,
            'level' => $typeToLevel->getLevel($entry->type)
        ];

        $eventId = $this->client->getIdent($this->client->captureMessage($entry->message, [], $options));
        $this->clientExceptionCheck();

        return $eventId;
    }

    /**
     * @param array                $entries
     * @param TypeToLevelInterface $typeToLevel
     *
     * @return mixed
     */
    public function pushMultiline(array $entries, TypeToLevelInterface $typeToLevel)
    {
        $options = [
            'tags' => ['Source' => 'Log'],
            'level' => $typeToLevel->getLevel('info')
        ];

        $sentryLog = '';
        foreach ($entries as $logEntry) {
            $sentryLog .= $logEntry->message;
        }

        $eventId = $this->client->getIdent($this->client->captureMessage($sentryLog, [], $options));
        $this->clientExceptionCheck();

        return $eventId;
    }

    /**
     * @throws SentryException
     */
    private function clientExceptionCheck()
    {
        $lastError = $this->client->getLastError();

        if (null !== $lastError) {
            throw new SentryException(sprintf('Error "%s" with the event id "%s" occurred while send log to sentry.', $lastError, $eventId));
        }
    }
}
