<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType, SubmitType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\{Length, NotBlank};

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'mapped'      => false,
                'constraints' => [
                    new UserPassword()
                ],
                'attr'        => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'mapped'        => false,
                'type'          => PasswordType::class,
                'constraints'   => [
                    new NotBlank(),
                    new Length(min: 5, max: 128)
                ],
                'first_options' => [
                    'hash_property_path' => 'password'
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
