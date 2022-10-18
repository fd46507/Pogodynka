<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;
use App\Repository\WeatherRepository;
use App\Repository\CityRepository;
use Symfony\Component\Config\Definition\Exception\Exception;

class WeatherController extends AbstractController
{
    public function cityAction($country, $city, CityRepository $cityRepository, WeatherRepository $weatherRepository): Response
    {
        $city = $cityRepository->findCityByCountryAndName($country, $city);
        if ($city == 0)
        {
            throw new Exception('Page not found');
        }
        $weather = $weatherRepository->findByCityId($city[0]->getId());
        return $this->render('weather/city.html.twig', [
            'city' => $city[0],
            'weather' => $weather[0],
        ]);
    }
}
