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

        $eventId = $this->pushToSentry($entry->message, $options);

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
        $sentryLog = '';
        foreach ($entries as $logEntry) {
            $sentryLog .= $logEntry->message;
        }

        $options = [
            'tags' => ['Source' => 'Log'],
            'level' => $typeToLevel->getLevel('info'),
            'extra' => ['log' => $sentryLog]
        ];

        $now = new \DateTime();
        $message = sprintf('Formless Log %s', $now->format('d-m-Y'));

        $eventId = $this->pushToSentry($message, $options);

        return $eventId;
    }

    /**
     * @param string $sentryLog
     * @param array  $options
     *
     * @throws SentryException
     *
     * @return int
     */
    private function pushToSentry($sentryLog, array $options)
    {
        $eventId = $this->client->getIdent($this->client->captureMessage($sentryLog, [], $options));
        $lastError = $this->client->getLastError();

        if (null !== $lastError) {
            throw new SentryException(sprintf('Error "%s" with the event id "%s" occurred while send log to sentry.', $lastError, $eventId));
        }

        return $eventId;
    }
}
