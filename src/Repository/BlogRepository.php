<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    private $paginatorInterface;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Blog::class);
        $this->paginatorInterface = $paginatorInterface;
    }
    public function paginationQuery()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.id', 'desc')
            ->getQuery();
    }
    public function promoteQuery()
    {
        $query = $this->createQueryBuilder('b')
            ->where('b.promote = :promote')
            ->setParameter('promote', true)
            ->orderBy('b.id', 'DESC')
            ->setMaxResults(2)
            ->getQuery();

        return $query->getResult();
    }
    public function findSearch(SearchData $searchData) : PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->where('b.title LIKE :title');
          //  ->orWhere('b.content LIKE :content');

        if (!empty($searchData->q)) {
            $queryBuilder = $queryBuilder
                ->andWhere('b.title LIKE :q');
              //  ->orWhere('b.content LIKE :q');
        }

        $queryBuilder = $queryBuilder
            ->setParameter('title', '%' . $searchData->q . '%')
           // ->setParameter('content', '%' . $searchData->q . '%')
            ->setParameter('q', '%' . $searchData->q . '%');

        $data = $queryBuilder->getQuery()->getResult();

        $blogs = $this->paginatorInterface->paginate(
            $data, $searchData->page, 12
        );

        return $blogs;

    }
//    /**
//     * @return Blog[] Returns an array of Blog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Blog
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
