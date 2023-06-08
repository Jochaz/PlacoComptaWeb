<?php

namespace App\Form;

use App\Entity\CategorieMateriaux;
use App\Entity\Materiaux;
use App\Entity\TVA;
use App\Entity\UniteMesure;
use App\Repository\UniteMesureRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MateriauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Designation', options:[
                'label' => 'Désignation'
            ])
            ->add('PrixAchat', options:[
                'label' => 'Prix d\'achat fournisseur'
            ])
            ->add('PrixUnitaire', options:[
                'label' => 'Prix unitaire (HT)'
            ])
            ->add('TVA', EntityType::class, [
                'class' => TVA::class,
                'choice_label' => function($TVA){
                    return $TVA->getLibelleTVAComplet();
                },
                'label' => 'TVA par défaut'
            ])
            ->add('Categorie', EntityType::class, [
                'class' => CategorieMateriaux::class,
                'choice_label' => 'Libelle',
                'label' => 'Catégorie du matériaux'
            ])
            ->add('UniteMesure', EntityType::class, [
                'class' => UniteMesure::class,
                'choice_label' => 'Libelle',
                'label' => 'Unité du matériaux',
                'query_builder' => function(UniteMesureRepository $umr){
                    return $umr->createQueryBuilder('um')
                                ->orderby('um.NumOrdre');                
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiaux::class,
        ]);
    }
}
