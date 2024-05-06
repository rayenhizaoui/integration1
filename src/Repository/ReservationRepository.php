<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

   /**
     * @return Reservation[] Returns an array of Reservation objects
    */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.nom = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.nom = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

   public function getReservationStats(): array
    {
       $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
           'SELECT e.nom, COUNT(r.id) as reservation_count 
         FROM App\Entity\Reservation r
         JOIN r.idEquipement e
         GROUP BY e.id'
        );

        return $query->getResult();
    }
    public function findAllPaginated($page, $perPage = 10)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        return $queryBuilder->getQuery()->getResult();
    }
    /*public function getReservationStats()
{
    return $this->createQueryBuilder('r')
        ->select('e.nom as nomEquipement, COUNT(r.id) as reservation_count')
        ->join('r.idEquipement', 'e')
        ->groupBy('nomEquipement') // Utiliser l'alias nomEquipement pour le groupement
        ->getQuery()
        ->getResult();
}*/


}
