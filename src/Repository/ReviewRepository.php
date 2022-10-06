<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Common\Collections\Criteria;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findOneByIdJoinedToPlace(int $reviewId): ?Review
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT r, p
            FROM App\Entity\Review r
            INNER JOIN r.place p
            WHERE r.id = :id'
        )->setParameter('id', $reviewId);

        return $query->getOneOrNullResult();
    }

    public static function reviewsByUserAndPlace(): Criteria
    {
        //$user = getUser($this->getUser());

        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('user', true));
    }

    public function save(Review $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Review $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Review[] Returns an array of Review objects
//     */
    public function findByPlace($placeId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.place = :placeId')
            ->setParameter('placeId', $placeId)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUser($review): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.review = :review')
            ->setParameter('review', $review)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Review
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
