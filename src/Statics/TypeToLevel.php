<?php

namespace MoveElevator\Sentry\LogPusher\Statics;

/**
 * @package MoveElevator\Sentry\LogPusher\Statics
 */
class TypeToLevel implements TypeToLevelInterface
{
    /**
     * @param string $type
     *
     * @return string
     */
    public function getLevel($type)
    {
        $typeToLevel = [
            'error' => \Raven_Client::ERROR,
            'warn' => \Raven_Client::WARNING
        ];

        if (true === isset($typeToLevel[$type])) {
            return $typeToLevel[$type];
        }

        return \Raven_Client::INFO;
    }
}
