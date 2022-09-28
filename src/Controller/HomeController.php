<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\CategoryRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/about', name: 'app_home_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/contact', name: 'app_home_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/category', name: 'app_home_category', methods: ['GET'])]
    public function category(CategoryRepository $categoryRepository): Response
    {
        return $this->render('home/category.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/places', name: 'app_home_places', methods: ['GET'])]
    public function allPlaces(PlaceRepository $placeRepository): Response
    {
        return $this->render('home/places.html.twig', [
            'places' => $placeRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_place_show', methods: ['GET'])]
    public function show(Place $place): Response
    {
        return $this->render('home/place.html.twig', [
            'place' => $place,
        ]);
    }
}
