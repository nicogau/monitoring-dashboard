<?php

namespace App\Form;

use App\Entity\Vhost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VhostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('isActive')
            // ->add('tlsExpDate')
            // ->add('tlsRegistrarName')
            ->add('hostname')
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('tlsDayleft')
            ->add('server')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vhost::class,
        ]);
    }
}
