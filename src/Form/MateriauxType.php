<?php

namespace App\Form;

use App\Entity\CategorieMateriaux;
use App\Entity\Materiaux;
use App\Entity\ModelePiece;
use App\Entity\TVA;
use App\Entity\UniteMesure;
use App\Repository\ModelePieceRepository;
use App\Repository\UniteMesureRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;

class MateriauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Designation', options:[
                'label' => 'Désignation'
            ])
            ->add('PrixAchat', MoneyType::class, options:[
                'label' => 'Prix d\'achat fournisseur',
                'constraints' => [
                    new Positive(
                        message: 'Le prix d\'achat ne peut pas être négatif'
                    )
                ],
                'required'          => false,
                'invalid_message' => 'La valeur du prix d\'achat est invalide.',
            ])
            ->add('PrixUnitaire', MoneyType::class, options:[
                'label' => 'Prix unitaire (HT)',
                'constraints' => [
                    new Positive(
                        message: 'Le prix unitaire ne peut pas être négatif'
                    )
                ],
                'invalid_message' => 'La valeur du prix unitaire est invalide.',
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
            ->add('modelePieces', EntityType::class, options:[
                'query_builder' => function (ModelePieceRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->where('m.Plus_utilise = false')
                        ->orderBy('m.Libelle', 'ASC');
                },
                'class' => ModelePiece::class,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Inclus dans les modèles de pièce'
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
