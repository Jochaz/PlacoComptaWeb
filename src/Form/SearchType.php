<?php

namespace App\Form;

use App\Entity\CategorieMateriaux;
use App\Model\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Recherche par désignation',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Recherche par désignation...',
                    'value' => '',
                 
                ]
            ])
            ->add('categorie', EntityType::class, [
                'class' => CategorieMateriaux::class,
                'expanded' => true,
                'multiple' => true,
                'label' => 'Recherche par catégories',
            ])
            ->add('prixminachat', MoneyType::class, [
                'label' => 'Prix min. achat',
                'invalid_message' => 'La valeur du prix min. d\'achat est invalide.',
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('prixmaxachat', MoneyType::class, [
                'label' => 'Prix max. achat',
                'invalid_message' => 'La valeur du prix max. d\'achat est invalide.',
                'required' => false,
                'empty_data' => '9999999',
            ])
            ->add('prixminunitaire', MoneyType::class, [
                'label' => 'Prix min. unitaire',
                'invalid_message' => 'La valeur du prix min. unitaire est invalide.',
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('prixmaxunitaire', MoneyType::class, [
                'label' => 'Prix max. unitaire',
                'invalid_message' => 'La valeur du prix max. unitaire est invalide.',
                'required' => false,
                'empty_data' => '9999999',
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }


}
?>