<?php

namespace App\Form;

use App\Model\SearchParticulierData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchParticulierType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Recherche par nom du client',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Nom...',
                    'value' => '',
                 
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Recherche par prénom du client',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Prénom...',
                    'value' => '',
                 
                ]
            ])
            ->add('adresseemail', TextType::class, [
                'label' => 'Recherche par adresse email du client',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Adresse email...',
                    'value' => '',
                 
                ]
            ])
            ->add('numerotelephoneportable', TextType::class, [
                'label' => 'Recherche par numéro de téléphone portable du client',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Numéro de téléphone portable...',
                    'value' => '',
                 
                ]
            ])
            ->add('numerotelephonefixe', TextType::class, [
                'label' => 'Recherche par numéro de téléphone fixe du client',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Numéro de téléphone fixe...',
                    'value' => '',
                 
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchParticulierData::class,
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