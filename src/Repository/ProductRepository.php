<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginatorInterface)
    {
        parent::__construct($registry, Product::class);
        $this->paginatorInterface = $paginatorInterface;
    }
    public function paginationQuery()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'desc')
            ->getQuery();
    }
    /**
     * Method Search $search
     *
     */
    public function findBySearchProduct(SearchData $searchData) : PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.name LIKE :name');
        //  ->orWhere('b.content LIKE :content');

        if (!empty($searchData->q)) {
            $queryBuilder = $queryBuilder
                ->andWhere('p.name LIKE :q');
        }

        $queryBuilder = $queryBuilder
            ->setParameter('name', '%' . $searchData->q . '%')
            ->setParameter('q', '%' . $searchData->q . '%');

        $data = $queryBuilder->getQuery()->getResult();
        $products = $this->paginatorInterface->paginate(
            $data, $searchData->page, 12
        );
        return $products;
    }


//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
