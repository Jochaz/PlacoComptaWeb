<?php

namespace App\Form;

use App\Entity\Particulier;
use App\Model\SearchFactureData;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFactureType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('NumFacture', TextType::class, [
                'label' => 'Recherche par numéro de facture',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Recherche par numéro de facture...',
                    'value' => '',
                 
                ]
            ])
            ->add('client', TextType::class, [
                'label' => 'Recherche par client',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Recherche par client...',
                    'value' => '',  
                ]
            ])
            ->add('prixminTTC', MoneyType::class, [
                'label' => 'Prix min. TTC',
                'invalid_message' => 'La valeur du prix min. TTC.',
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('prixmaxTTC', MoneyType::class, [
                'label' => 'Prix max. TTC',
                'invalid_message' => 'La valeur du prix max. TTC.',
                'required' => false,
                'empty_data' => '9999999',
            ])

            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchFactureData::class,
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