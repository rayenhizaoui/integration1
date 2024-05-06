<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class EvenementRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Evenement::class);
        $this->paginator = $paginator;
    }

    public function findAllPaginated(int $page = 1, int $limit = 10): PaginationInterface
    {
        $query = $this->createQueryBuilder('e')
            ->orderBy('e.id', 'ASC')
            ->getQuery();

        return $this->paginator->paginate($query, $page, $limit);
    }

    /**
     * Recherche les événements par leur nom.
     *
     * @param string $searchTerm Le terme de recherche
     * @return Evenement[] Les événements correspondants au terme de recherche
     */
    public function findBySearchCriteria(string $searchTerm, ?string $location, ?\DateTimeInterface $date): array
    {
        $queryBuilder = $this->createQueryBuilder('e');
        
        if ($searchTerm) {
            $queryBuilder->andWhere('e.nomevent LIKE :searchTerm')
                        ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }
        
        if ($location) {
            // Modifiez la partie de la requête ci-dessous pour trier par la colonne "lieu"
            $queryBuilder->orderBy('e.lieu', 'ASC')
                        ->andWhere('e.lieu LIKE :location')
                        ->setParameter('location', '%' . $location . '%');
        }
        
        if ($date) {
            $queryBuilder->andWhere('e.dateevent = :date')
                        ->setParameter('date', $date);
        }
        
        return $queryBuilder->orderBy('e.id', 'ASC')
                            ->getQuery()
                            ->getResult();
    }
    
    
}
