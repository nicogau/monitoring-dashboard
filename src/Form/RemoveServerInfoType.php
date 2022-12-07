<?php

namespace App\Form;

use App\Entity\ServerInfo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemoveServerInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('ip', TextType::class, [
            //     'label' => false,
            //     'required' => true,
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'ce champ ne doit pas être vide',
            //         ]),
            //         new Ip([
            //             'message' => 'ip invalide',
            //         ]),
            //     ],
            // ])
        ->add('server', EntityType::class, [
            'label' => false,
            'class' => ServerInfo::class,
            'choice_label' => 'ip'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
         $resolver->setDefaults([
            //  'data_class' => ServerInfo::class,
         ]);
    }
}
