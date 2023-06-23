<?php

namespace App\Form;

use App\Entity\CategorieMateriaux;
use App\Entity\TVA;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieMateriauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Libelle', options:[
                'label' => 'Libellé'
            ])
            ->add('TVADefaut', EntityType::class, [
                'class' => TVA::class,
                'choice_label' => function($TVA){
                    return $TVA->getLibelleTVAComplet();
                },
                'label' => 'TVA par défaut'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategorieMateriaux::class,
        ]);
    }
}
