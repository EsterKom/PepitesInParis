<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Place;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        //set Categories
        $categories=['Buy', 'See', 'Eat'];
        $dataCategories = [];
        for ($i = 0; $i < count($categories); $i++) {
        $category = new Category();
        $category->setName($categories[$i]);
        $dataCategories[] = $category;
        $manager->persist($category);
    }

    //cree admin
    $admin = new User();
    $admin->setUserName('Admina');
    $admin->setEmail('admin@pip.com');
    $admin->setRoles(['ROLE_ADMIN']);
    $password = $this->hasher->hashPassword($admin, 'pip123');
    $admin->setPassword($password);
    $manager->persist($admin);

    //cree user

    $dataUsers=[];
    for ($i = 1; $i < 4; $i++) {
        $user = new User();
        $user->setUserName($faker->firstName());
        $user->setRoles(['ROLE_USER']);
        $user->setEmail('user'.$i.'@user.com');
        $password = $this->hasher->hashPassword($user, 'user123');
        $user->setPassword($password);
        $dataUsers[] = $user;
        $manager->persist($user);
        }

        
        //set Places
        $dataPlaces = [];
        for ($i = 0; $i< 15; $i++) {
            $place = new Place();
            $place
                ->setName($faker->sentence($nbWords = 4, $variableNbWords = true))
                ->setSubtitle($faker->sentence($nbWords = 10, $variableNbWords = true))
                ->setDescription($faker->sentence($nbWords = 100, $variableNbWords = true))
                ->setCategory($faker->randomElement($dataCategories))
                ->setUser($faker->randomElement($dataUsers))
                ->getReview($faker->randomElement($dataReviews));
                //->setImage($faker->imageUrl(640, 480, 'animals', true));
        $dataPlaces[]= $place;  
        $manager->persist($place);
        }

        //set Reviews
        $dataReviews = [];
        for ($i = 0; $i< 15; $i++) {
            $review = new review();
            $review
                ->setRating($faker->numberBetween($min = 0, $max = 5))
                ->setComment($faker->sentence($nbWords = 30, $variableNbWords = true))
                ->setUser($faker->randomElement($dataUsers))
                ->setPlace($faker->randomElement($dataPlaces));
        $dataReviews[]= $review;  
        $manager->persist($review);
        }

        $manager->flush();
    }
}