<?php

namespace App\Repository;

use App\Entity\Meme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Meme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meme[]    findAll()
 * @method Meme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meme::class);
    }

    public function findByQuery($query){
        return $this->createQueryBuilder('m')
            ->andWhere('upper(m.title) LIKE upper(:query)')
            ->setParameter('query', '%'. $query .'%')
            ->getQuery()
            ->getResult();
    }

    public function findByRating(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT m
            FROM App\Entity\Meme m
            LEFT JOIN m.likes l
            GROUP BY m.id
            ORDER BY count(l.id) DESC"
        );
        return $query->getResult();
    }

    public function findByRatingAndDate(int $interval){
        $entityManager = $this->getEntityManager();
        $date = new \DateTime();
        $date->sub(new \DateInterval('P'. $interval .'D'));

        $query = $entityManager->createQuery(
            "SELECT m
            FROM App\Entity\Meme m
            LEFT JOIN m.likes l
            WHERE m.created_at >= :date
            GROUP BY m.id
            ORDER BY count(l.id) DESC"
        )->setParameter('date', $date);
        return $query->getResult();
    }

    // /**
    //  * @return Meme[] Returns an array of Meme objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Meme
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
