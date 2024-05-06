<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\GreaterThan;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8, 'max' => 8]),
                    new Regex(['pattern' => '/^\d+$/']),
                ],
            ])
            ->add('username', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8]),
                ],
            ])
            ->add('numero', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8, 'max' => 8]),
                    new Regex(['pattern' => '/^\d+$/']),
                ],
            ])
            ->add('email', null, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('adresse', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('password', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8]),
                    new Regex(['pattern' => '/\d/']),
                ],
            ])
            ->add('role', null, [
                'constraints' => [
                    new NotBlank(),

                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
