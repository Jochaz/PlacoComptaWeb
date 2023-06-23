<?php

namespace App\Form;

use App\Entity\CategorieMateriaux;
use App\Entity\UniteMesure;
use App\Repository\UniteMesureRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;

class UniteMesureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Libelle', options:[
                'label' => 'Libellé'
            ])
            ->add('Abreviation', options:[
                'label' => 'Abréviation',

            ])
            ->add('NumOrdre', options:[
                'label' => 'Numéro de l\'ordonnancement',
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UniteMesure::class,
        ]);
    }
}
