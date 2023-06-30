<?php

namespace App\Form;

use App\Entity\AdresseDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseChantierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Ligne1', options:[
                'label' => 'Ligne 1 de l\'adresse'
            ])
            ->add('Ligne2', options:[
                'label' => 'Ligne 2 de l\'adresse',
                'required' => false,
            ])
            ->add('Ligne3', options:[
                'label' => 'Ligne 3 de l\'adresse',
                'required' => false,
            ])
            ->add('Ville', options:[
                'label' => 'Ville'
            ])
            ->add('CP', options:[
                'label' => 'Code postale'
            ])
            ->add('BoitePostale', options:[
                'label' => 'Boite postale',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdresseDocument::class,
        ]);
    }
}
