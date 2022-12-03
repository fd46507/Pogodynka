<?php

namespace App\Service;

use App\Repository\WeatherRepository;
use App\Repository\CityRepository;

class WeatherUtil
{
    private CityRepository $cityRepository;
    private WeatherRepository $weatherRepository;
    public function __construct(CityRepository $cityRepository, WeatherRepository $weatherRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->weatherRepository = $weatherRepository;
    }
    public function getWeatherForCountryAndCity($countryCode, $cityName, &$cityEntity = null): array
    {
        $city = $this->cityRepository->findCityByCountryAndName($countryCode, $cityName);
        $cityEntity = $city[0];
        return $this->getWeatherForLocation($city[0]);
    }

    public function getWeatherForLocation($location, $flag = FALSE): array
    {
        if ($flag == FALSE)
        {
            $weathers = $this->weatherRepository->findByCityId($location->getId());
        }
        else
        {
            $weathers = $this->weatherRepository->findByCityId($location);
        }
        return $weathers;
    }
}