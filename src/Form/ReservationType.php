<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('datedebutres')
            ->add('datefinres')
            ->add('type')
            ->add('deposit')
            ->add('idEquipement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
    // Dans votre ReservationRepository.php

public function findByNom($nom)
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.nom LIKE :nom')
        ->setParameter('nom', '%'.$nom.'%')
        ->getQuery()
        ->getResult();
}

}
