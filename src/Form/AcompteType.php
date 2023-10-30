<?php

namespace App\Form;

use App\Entity\Acompte;
use App\Entity\ModeReglement;
use App\Repository\ModeReglementRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Montant', options:[
                'label' => 'Montant de l\'acompte du devis (10% du prix total TTC du devis)',
                'required' => false,
            ])
            ->add('ModeReglement', options:[
                'label' => 'Mode de règlement de l\'acompte',
                'class' => ModeReglement::class,
                'attr' => ['required' => true],
                'query_builder' => function (ModeReglementRepository $er) {
                    return $er->createQueryBuilder('mr');
                },
                'multiple' => false,
                'expanded' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Acompte::class,
        ]);
    }
}
