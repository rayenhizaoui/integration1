<?php

namespace App\Repository;

use App\Entity\Recompense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @extends ServiceEntityRepository<Recompense>
 *
 * @method Recompense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recompense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recompense[]    findAll()
 * @method Recompense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecompenseRepository extends ServiceEntityRepository
{
private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recompense::class);
        $this->paginator = $paginator;
    }

    public function findAllPaginated(int $page = 1, int $limit = 10): PaginationInterface
    {
        $query = $this->createQueryBuilder('r')
            ->orderBy('r.id', 'ASC')
            ->getQuery();

        return $this->paginator->paginate($query, $page, $limit);
    }

//    /**
//     * @return Recompense[] Returns an array of Recompense objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recompense
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}


