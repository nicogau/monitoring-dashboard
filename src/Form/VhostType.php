<?php

namespace App\Form;

use App\Entity\Vhost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class VhostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hostname', TextType::class, [
                'label' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('submit',SubmitType::class, [
                'label' => 'valider'
            ])
            // ->add('server')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // $resolver->setDefaults([
        //     'data_class' => Vhost::class,
        // ]);
    }
}
