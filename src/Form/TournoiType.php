<?php

namespace App\Form;

use App\Entity\Local;
use App\Entity\Tournoi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TournoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomTournoi')
            ->add('NomEquipe')
            ->add('NombreParticipants')
            ->add('duree')
            ->add('typeJeu', ChoiceType::class,[
                'choices'  => [
                    'Overwatch' => 'Overwatch',
                    'FIFA'     => 'FIFA',
                    'Solo Game'    => 'Solo Game',
                    'Fortnite'    => 'Fortnite',
                    'Valorant'    => 'Valorant',
                ]
            ])
            ->add('fraisParticipant')
            ->add('Location',  EntityType::class, [
                    'choice_label' => 'Adresse',
                    'class' => 'App\Entity\Local'
                ]
            )
            ->add('image', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournoi::class,
        ]);
    }
}
