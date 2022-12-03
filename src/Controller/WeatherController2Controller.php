<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Form\WeatherType;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/weather')]
class WeatherController2Controller extends AbstractController
{
    #[Route('/', name: 'app_weather_controller2_index', methods: ['GET'])]
    public function index(WeatherRepository $weatherRepository): Response
    {
        return $this->render('weather_controller2/index.html.twig', [
            'weather' => $weatherRepository->findAll(),
        ]);
    }


    #[IsGranted('ROLE_WEATHER_NEW')]
    #[Route('/new', name: 'app_weather_controller2_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WeatherRepository $weatherRepository): Response
    {
        $weather = new Weather();
        $form = $this->createForm(WeatherType::class, $weather);
        $form->handleRequest($request);

        try
        {
            $weather->setCityname($weather->getCity()->getName());
        }
        catch (\Throwable $e) {
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $weatherRepository->save($weather, true);

            return $this->redirectToRoute('app_weather_controller2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('weather_controller2/new.html.twig', [
            'weather' => $weather,
            'form' => $form,
        ]);
    }


    #[IsGranted('ROLE_WEATHER_SHOW')]
    #[Route('/{id}', name: 'app_weather_controller2_show', methods: ['GET'])]
    public function show(Weather $weather): Response
    {
        return $this->render('weather_controller2/show.html.twig', [
            'weather' => $weather,
        ]);
    }

    #[IsGranted('ROLE_WEATHER_EDIT')]
    #[Route('/{id}/edit', name: 'app_weather_controller2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Weather $weather, WeatherRepository $weatherRepository): Response
    {
        $form = $this->createForm(WeatherType::class, $weather);
        $form->handleRequest($request);

        $weather->setCityname($weather->getCity()->getName());

        if ($form->isSubmitted() && $form->isValid()) {
            $weatherRepository->save($weather, true);

            return $this->redirectToRoute('app_weather_controller2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('weather_controller2/edit.html.twig', [
            'weather' => $weather,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_WEATHER_DELETE')]
    #[Route('/{id}', name: 'app_weather_controller2_delete', methods: ['POST'])]
    public function delete(Request $request, Weather $weather, WeatherRepository $weatherRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weather->getId(), $request->request->get('_token'))) {
            $weatherRepository->remove($weather, true);
        }

        return $this->redirectToRoute('app_weather_controller2_index', [], Response::HTTP_SEE_OTHER);
    }
}
