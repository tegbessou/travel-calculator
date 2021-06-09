<?php

namespace App\Factory;

use App\Entity\Airport;

class AirportFactory
{
    public static function initialize(string $name, float $latitude, float $longitude, string $countryCode, string $city, string $iotaCode): Airport
    {
        $airport = new Airport();
        $airport->name = $name;
        $airport->latitude = $latitude;
        $airport->longitude = $longitude;
        $airport->countryCode = $countryCode;
        $airport->city = $city;
        $airport->iotaCode = $iotaCode;

        return $airport;
    }
}