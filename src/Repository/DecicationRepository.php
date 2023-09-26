<?php

namespace App\Repository;

use App\Entity\Decication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Decication>
 *
 * @method Decication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Decication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Decication[]    findAll()
 * @method Decication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Decication::class);
    }

    public function findDecicationNext(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.dateDedication > :val')
            ->setParameter('val', new \DateTime())
            ->orderBy('d.dateDedication', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
//    /**
//     * @return Decication[] Returns an array of Decication objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Decication
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
