<?php

namespace App\Repository;

use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipe::class);
    }

    public function search($searchTerm)
    {
        return $this->createQueryBuilder('e')
            ->where('e.nom LIKE :searchTerm')
            ->orWhere('e.nbrJoueur LIKE :searchTerm')
            ->orWhere('e.listeJoueur LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
    }

    public function countAllTeams(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function calculateAveragePlayersPerTeam(): float
    {
        $totalTeams = $this->countAllTeams(); // Obtenez le nombre total d'équipes
        $totalPlayers = 0;
    
        // Obtenez le nombre total de joueurs dans toutes les équipes
        $teams = $this->findAll();
        foreach ($teams as $team) {
            $totalPlayers += $team->getNbrJoueur();
        }
    
        // Calculez la moyenne des joueurs par équipe
        if ($totalTeams > 0) {
            return $totalPlayers / $totalTeams;
        } else {
            return 0.0; // Retourne 0 si aucune équipe n'est trouvée pour éviter une division par zéro
        }
    }

    public function findTeamsWithEnoughPlayers(): array
    {
        $threshold = 50; // Nombre minimum de joueurs considéré comme suffisant

        return $this->createQueryBuilder('e')
            ->where('e.nbrJoueur >= :threshold')
            ->setParameter('threshold', $threshold)
            ->getQuery()
            ->getResult();
    }

    public function findTeamsWithInsufficientPlayers(): array
    {
        $threshold = 50; // Nombre minimum de joueurs considéré comme suffisant

        return $this->createQueryBuilder('e')
            ->where('e.nbrJoueur < :threshold')
            ->setParameter('threshold', $threshold)
            ->getQuery()
            ->getResult();
    }
    public function findAllSorted(string $sortBy, string $sortOrder): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy("e.$sortBy", $sortOrder)
            ->getQuery()
            ->getResult();
    }
}
