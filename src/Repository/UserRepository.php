<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function isBanned(User $user): bool
    {
        return $user->isBanned();
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /*
    public function findBySearchTerm($searchTerm)
    {
        $queryBuilder = $this->createQueryBuilder('u');

        // Ajoutez les conditions de recherche
        $queryBuilder->where(
            $queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('u.id', ':searchTerm'),
                $queryBuilder->expr()->like('u.cin', ':searchTerm'),
                $queryBuilder->expr()->like('u.username', ':searchTerm'),
                $queryBuilder->expr()->like('u.numero', ':searchTerm'),
                $queryBuilder->expr()->like('u.email', ':searchTerm'),
                $queryBuilder->expr()->like('u.adresse', ':searchTerm'),
                $queryBuilder->expr()->like('u.password', ':searchTerm'),
                $queryBuilder->expr()->like('u.role', ':searchTerm')
            )
        );

        // Associez les paramètres
        $queryBuilder->setParameter('searchTerm', '%' . $searchTerm . '%');

        // Exécutez la requête et récupérez les résultats
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }*/
}
