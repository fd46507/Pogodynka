<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;
use App\Entity\Weather;
use App\Repository\WeatherRepository;
use App\Repository\CityRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Service\WeatherUtil;
use Symfony\Component\HttpFoundation\JsonResponse;

class WeatherApiController extends AbstractController
{
    #[Route('/weather/api', name: 'app_weather_api')]
    public function weather(Request $request, WeatherUtil $weatherUtil): Response
    {
        $payload = $request->getContent();
        $payload = json_decode($payload, TRUE);

        $weather = $weatherUtil->getWeatherForCountryAndCity($payload['country'], $payload['city']);
        
        if ($payload['format'] == "json")
        {
            $json_array = array('City' => $weather[0]->getCityname(),
                                'Weather type' => $weather[0]->getType(),
                                'Temperature' => $weather[0]->getTemperature(),
                                'Wind' => $weather[0]->getWind(),
                                'Humidity' => $weather[0]->getHumidity(),
                                'Precipitation chance' => $weather[0]->getPrecipitation());
            return new JsonResponse($json_array);
        }
        else if ($payload['format'] == "csv")
        {
            $column_names = ['City', 'Weather type', 'Temperature', 'Wind', 'Humidity' , 'Precipitation chance'];
            $csv_array = array('City' => $weather[0]->getCityname(),
                                'Weather type' => $weather[0]->getType(),
                                'Temperature' => $weather[0]->getTemperature(),
                                'Wind' => $weather[0]->getWind(),
                                'Humidity' => $weather[0]->getHumidity(),
                                'Precipitation chance' => $weather[0]->getPrecipitation());

            $csv = implode(',', $column_names);
            $new_row = implode(',', $csv_array);
            $csv .= "\n" . $new_row;
            return new Response($csv);
        }
    }

    #[Route('/weather/api/{_format}', name: 'app_weather_api')]
    public function weatherTwig(Request $request, WeatherUtil $weatherUtil, $_format): Response
    {
        $weather = $weatherUtil->getWeatherForCountryAndCity($request->get('country'), $request->get('city'));
        
        if ($_format == 'json')
        {
            $json_array = array('City' => $weather[0]->getCityname(),
                                'Weather type' => $weather[0]->getType(),
                                'Temperature' => $weather[0]->getTemperature(),
                                'Wind' => $weather[0]->getWind(),
                                'Humidity' => $weather[0]->getHumidity(),
                                'Precipitation chance' => $weather[0]->getPrecipitation());

            return $this->render('weather_api/weather.json.twig', [
                'weather' => $json_array,
            ]);
        }
        else if ($_format == 'csv')
        {
            $csv_array = array('City' => $weather[0]->getCityname(),
                                'Weather type' => $weather[0]->getType(),
                                'Temperature' => $weather[0]->getTemperature(),
                                'Wind' => $weather[0]->getWind(),
                                'Humidity' => $weather[0]->getHumidity(),
                                'Precipitation chance' => $weather[0]->getPrecipitation());

            $csv = implode(',', $csv_array);
            return $this->render('weather_api/weather.csv.twig', [
                'weather' => $csv,
            ]);
        }
    }
}
