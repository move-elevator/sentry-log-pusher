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
     * @throws SentryException
     */
    public function push($entry, TypeToLevelInterface $typeToLevel)
    {
        $options = [
            'tags' => ['Source' => 'Log'],
            'extra' => (array) $entry,
            'level' => $typeToLevel->getLevel($entry->type)
        ];

        $eventId = $this->client->getIdent($this->client->captureMessage($entry->message, [], $options));
        $lastError = $this->client->getLastError();

        if (null !== $lastError) {
            throw new SentryException(sprintf('Error "%s" with the event id "%s" occurred while send log to sentry.', $lastError, $eventId));
        }

        return $eventId;
    }
}
