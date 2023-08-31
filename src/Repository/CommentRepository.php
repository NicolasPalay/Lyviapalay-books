<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */
    public function findByActiveProduct($activeValue,$product): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.product', 'p')
            ->andWhere('c.product = :product')
            ->setParameter('product', $product)
            ->andWhere('c.active = :active')
            ->setParameter('active', $activeValue)
            ->orderBy('c.id', 'desc')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByActiveBlog($activeValue,$blog): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.blog', 'b')
            ->andWhere('c.blog = :blog')
            ->setParameter('blog', $blog)
            ->andWhere('c.active = :active')
            ->setParameter('active', $activeValue)
            ->orderBy('c.id', 'desc')
            ->getQuery()
            ->getResult()
            ;
    }

//    public function findOneBySomeField($value): ?Comment
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
