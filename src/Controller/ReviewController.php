<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry; //added
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security; //added
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted; //added


#[Route('/review')]
class ReviewController extends AbstractController
{
// public function __construct(ManagerRegistry $registry)
// {
//     parent::__construct($registry, Review::class);
// }

    #[Route('/', name: 'app_review_index', methods: ['GET'])]
    public function index(ReviewRepository $reviewRepository): Response
    {
        $authosReviews = $reviewRepository->findBy(['user' => $this->getUser()]);

        return $this->render('review/index.html.twig', [
            'authoreviews' => $authosReviews
        ]);
    }

    #[Route('/new', name: 'app_review_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, ReviewRepository $reviewRepository): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUser($this->getUser());
            $reviewRepository->save($review, true);

            return $this->redirectToRoute('app_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('review/new.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_review_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Review $review, ManagerRegistry $doctrine, int $id): Response
    {
        // $place = $doctrine->getRepository(place::class)->findOneByIdJoinedToReview($id);
        // $review = $place->getReview();
        // dd($review);

        return $this->render('review/show.html.twig', [
            'review' => $review,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_review_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === place.getUser()")]
    public function edit(Request $request, Review $review, ReviewRepository $reviewRepository): Response
    {
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUser($this->getUser());
            $reviewRepository->save($review, true);

            return $this->redirectToRoute('app_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('review/edit.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_review_delete', methods: ['POST'])]
    #[Security("is_granted('ROLE_USER') and user === review.getUser()")]
    public function delete(Request $request, Review $review, ReviewRepository $reviewRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            $reviewRepository->remove($review, true);
        }

        return $this->redirectToRoute('app_review_index', [], Response::HTTP_SEE_OTHER);
    }
}
