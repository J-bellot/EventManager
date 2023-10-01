<?php

namespace App\Repository;

use App\Entity\Eventattendee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eventattendee>
 *
 * @method Eventattendee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eventattendee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eventattendee[]    findAll()
 * @method Eventattendee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventattendeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eventattendee::class);
    }

//    /**
//     * @return Eventattendee[] Returns an array of Eventattendee objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Eventattendee
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
