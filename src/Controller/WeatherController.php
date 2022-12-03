<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;
use App\Repository\WeatherRepository;
use App\Repository\CityRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Service\WeatherUtil;

class WeatherController extends AbstractController
{
    public function cityAction($country, $city, WeatherUtil $weatherUtil): Response
    {
        // $city = $cityRepository->findCityByCountryAndName($country, $city);
        // if ($city == 0)
        // {
        //     throw new Exception('Page not found');
        // }
        // $weather = $weatherRepository->findByCityId($city[0]->getId());
        $weather = $weatherUtil->getWeatherForCountryAndCity($country, $city, $cityEntity);
        return $this->render('weather/city.html.twig', [
            'city' => $cityEntity,
            'weather' => $weather[0],
        ]);
    }
}
