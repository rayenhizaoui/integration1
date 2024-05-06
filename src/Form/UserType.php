<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

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
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'User',

            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
