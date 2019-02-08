<?php

namespace App\Repository;

use App\Entity\LikeWod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LikeWod|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeWod|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeWod[]    findAll()
 * @method LikeWod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeWodRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LikeWod::class);
    }

//    /**
//     * @return LikeWod[] Returns an array of LikeWod objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LikeWod
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
