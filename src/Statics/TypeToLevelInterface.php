<?php

namespace MoveElevator\Sentry\LogPusher\Statics;

/**
 * @package MoveElevator\Sentry\LogPusher\Statics
 */
interface TypeToLevelInterface
{
    /**
     * @param string $type
     *
     * @return string
     */
    public function getLevel($type);
}
