<?php

namespace App\Repository;

use App\Controller\SearchController;
use App\Entity\Tournoi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;


/**
 * @extends ServiceEntityRepository<Tournoi>
 *
 * @method Tournoi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournoi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournoi[]    findAll()
 * @method Tournoi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournoiRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Tournoi::class);
        $this->paginator = $paginator;
    }


    public function findSearch(SearchController $search): SlidingPaginationInterface
    {
        $query = $this->createQueryBuilder('t');
        if (!empty($search->q)) {
            $query = $query
                ->andWhere('t.nomTournoi LIKE :q')
                ->setParameter('q', '%' . $search->q . '%');
        }
        if (!empty($search->min)) {
            $query = $query
                ->andWhere('t.fraisParticipant >=  :min')
                ->setParameter('min', $search->min);
        }
        if (!empty($search->max)) {
            $query = $query
                ->andWhere('t.fraisParticipant <=  :max')
                ->setParameter('max', $search->max);
        }
        if (!empty($search->typeJeu)) {
            $query = $query
                ->andWhere('t.typeJeu LIKE :typeJeu')
                ->setParameter('typeJeu', $search->typeJeu);
        }
        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            4
        );
    }
    //    /**
    //     * @return Tournoi[] Returns an array of Tournoi objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tournoi
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
