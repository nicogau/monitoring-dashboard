<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'les champs de mots de passe doivent correspondre',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'mot de passe'],
                'second_options' => ['label' => 'répéter le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'votre mot de passe doit être au moins de  {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            /* ->add('plainPassword', PasswordType::class, [ */
            /*     // instead of being set onto the object directly, */
            /*     // this is read and encoded in the controller */
            /*     'mapped' => false, */
            /*     'attr' => ['autocomplete' => 'new-password'], */
            /*     'constraints' => [ */
            /*         new NotBlank([ */
            /*             'message' => 'Entrer un mot de passe', */
            /*         ]), */
            /*         new Length([ */
            /*             'min' => 8, */
            /*             'minMessage' => 'votre mot de passe doit être au moins de  {{ limit }} caractères', */
            /*             // max length allowed by Symfony for security reasons */
            /*             'max' => 4096, */
            /*         ]), */
            /*     ], */
            /* ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
