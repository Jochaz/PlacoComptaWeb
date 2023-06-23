<?php

namespace App\Form;

use App\Entity\TVA;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TVAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Libelle', options:[
                'label' => 'Libellé'
            ])
            ->add('Taux', options:[
                'label' => 'Taux TVA',

            ])
            ->add('DateDebut', DateType::class, options:[
                'label' => 'Date de début d\'application',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'required' => false
            ])

            ->add('DateFin', DateType::class, options:[
                'label' => 'Date de fin d\'application',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TVA::class,
        ]);
    }
}
