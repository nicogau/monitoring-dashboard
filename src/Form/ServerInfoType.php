<?php

namespace App\Form;

use App\Entity\ServerInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\NotBlank;

class ServerInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'nom du serveur',
                'constraints' => [
                    new NotBlank(['message' => 'ce champ ne peut être vide']),
                ]
            ])
            ->add('sshHostKey', TextType::class, [
                'label' => 'hôte SSH',
                'constraints' => [
                    new NotBlank(['message' => 'ce champ ne peut être vide']),
                ]
            ])
            ->add('ip', TextType::class, [
                'label' => 'adresse IP',
                'constraints' => [
                    new NotBlank(['message' => 'ce champ ne peux être vide']),
                    new Ip(['message' => 'format Ip invalide'])
                ]
            ])
            // ->add('diskUsed')
            // ->add('diskFree')
            // ->add('diskSize')
            // ->add('memSize')
            // ->add('saveStateLast')
            // ->add('osType')
            // ->add('osVersion')
            // ->add('createdAt')
            // ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServerInfo::class,
        ]);
    }
}
