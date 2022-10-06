<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry; //added
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security; //added
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted; //added

#[Route('/place')]
class PlaceController extends AbstractController
{
    #[Route('/', name: 'app_place_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
     public function index(PlaceRepository $placeRepository): Response
     {
             return $this->render('place/index.html.twig', [
            'places' => $placeRepository->findAll(),
        ]);
     }

    // public function show(PlaceRepository $placeRepository): Response
    // {
    //     return $this->render('place/index.html.twig', [
    //           'places' => $placeRepository->findAll(),
    //     ]);
    // }    

    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_place_new', methods: ['GET', 'POST'])]
    
    public function new(Request $request, PlaceRepository $placeRepository): Response
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $place->setUser($this->getUser());
            $placeRepository->save($place, true);

            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('place/new.html.twig', [
            'place' => $place,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_place_show', methods: ['GET'])]
    // public function showOne(Place $place): Response
    // {
        //$placeId = $place->getId();
        //$allRating = $reviewRepository->findBy(['place' => $place]); //can also add $place->getId() inside ();
        //$allReviews = $reviewRepository->reviewsByUserAndPlace();
        
        // $reviews = $place->getReview()->getComment();
        // foreach($reviews as $review) {
        //     dump($review);
        // }

        //  $this->render('place/show.html.twig', [
        //      'place' => $place,
        //     'allRating' => $allRating,
        //    'allReviews' => $place->reviewsByUserAndPlace()
    //      ]);
    // }

    #[Route('/{id}', name: 'app_place_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Place $place): Response
    {
        return $this->render('place/show.html.twig', [
            'place' => $place,
        ]);
    }

    #[Security("is_granted('ROLE_USER') and user === place.getUser()")]
    #[Route('/{id}/edit', name: 'app_place_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Place $place, PlaceRepository $placeRepository): Response
    {
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $place->setUser($this->getUser());
            $placeRepository->save($place, true);

            return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('place/edit.html.twig', [
            'place' => $place,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_place_delete', methods: ['POST'])]
    #[Security("is_granted('ROLE_USER') and user === place.getUser()")]
    public function delete(Request $request, Place $place, PlaceRepository $placeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$place->getId(), $request->request->get('_token'))) {
            $placeRepository->remove($place, true);
        }

        return $this->redirectToRoute('app_place_index', [], Response::HTTP_SEE_OTHER);
    }
}
