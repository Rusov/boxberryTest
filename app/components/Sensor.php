<?php

namespace app\components;

class Sensor
{
    const PRESSURE_MIN = 100;

    const PRESSURE_MAX = 129;

    const PULSE_MIN = 60;

    const PULSE_MAX = 75;

    public static function isNormal($pulse, $pressure): bool
    {
        if ($pulse > self::PULSE_MAX || $pulse < self::PULSE_MIN) {
            return false;
        }

        if ($pressure > self::PRESSURE_MAX || $pressure < self::PRESSURE_MIN) {
            return false;
        }

        return true;
    }
}
