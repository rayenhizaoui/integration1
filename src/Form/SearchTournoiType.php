<?php

namespace App\Form;

use App\Controller\SearchController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTournoiType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('q', TextType::class, [
            'label' =>false,
            'required' =>false,
            'attr'=> [
                'placeholder'=>'search'
            ]

        ])
        ->add('typeJeu', ChoiceType::class,[
            'label'=>false,
            'required' => false,
            'expanded' => true,

            'choices'  => [
                'Overwatch' => 'Overwatch',
                'FIFA'     => 'FIFA',
                'Solo Game'    => 'Solo Game',
                'Fortnite'    => 'Fortnite',
                'Valorant'    => 'Valorant',
            ]
        ])
        ->add('min', NumberType::class,[
            'label' =>false,
            'required' =>false,
            'attr'=> [
                'placeholder'=>'min Price'
            ]

        ])
        ->add('max', NumberType::class,[
            'label' =>false,
            'required' =>false,
            'attr'=> [
                'placeholder'=>'max Price'
            ]

        ]);
}


    public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => SearchController::class,
        'method' => 'GET',
        'csrf_protection' => false
    ]);
}
public  function getBlockPrefix()
{
    return '';
}

}