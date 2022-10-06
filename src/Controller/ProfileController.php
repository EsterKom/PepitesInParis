<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(Reviewrepository $reviewrepository): Response
    {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
        // 'reviews' => $reviewrepository->findbyUser($user)
        ]);
    }
}
