<?php

namespace App\Form;

use App\Entity\Materiaux;
use App\Entity\ModelePiece;
use App\Repository\MateriauxRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelePieceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Libelle', options:[
                'label' => 'Libellé du modèle de pièce'
            ])
            ->add('Materiaux', EntityType::class, options:[
                'class' => Materiaux::class,
                'query_builder' => function (MateriauxRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->where('m.Plus_utilise = false')
                        ->orderBy('m.Designation', 'ASC');
                },
                'multiple' => true,
                'expanded' => true,
                'label' => 'Matériaux composant le modèle de pièce',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModelePiece::class,
        ]);
    }
}
