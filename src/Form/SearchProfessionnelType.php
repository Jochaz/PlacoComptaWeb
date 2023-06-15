<?php

namespace App\Form;

use App\Model\SearchDataProfessionnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProfessionnelType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomSociete', TextType::class, [
                'label' => 'Recherche par nom de société',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Nom de société...',
                    'value' => '',
                 
                ]
            ])
            ->add('SIRET', TextType::class, [
                'label' => 'Recherche par SIRET',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'SIRET...',
                    'value' => '',
                 
                ]
            ])
            ->add('SIREN', TextType::class, [
                'label' => 'Recherche par SIREN',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'SIREN...',
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
            'data_class' => SearchDataProfessionnel::class,
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