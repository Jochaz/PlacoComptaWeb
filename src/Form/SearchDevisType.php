<?php

namespace App\Form;

use App\Entity\EtatDocument;
use App\Entity\Particulier;
use App\Model\SearchDevisData;
use App\Repository\EtatDocumentRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchDevisType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numDevis', TextType::class, [
                'label' => 'Recherche par numéro de devis',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Recherche par numéro de devis...',
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
            ->add('etatDocument', EntityType::class, [
                'class' => EtatDocument::class,
                'query_builder' => function (EtatDocumentRepository $er) {
                    return $er->createQueryBuilder('ed')
                        ->where('ed.TypeDocument = 11')
                        ->orWhere('ed.TypeDocument = 10')
                        ->orderBy('ed.NumOrdre', 'ASC');
                },
                'expanded' => true,
                'multiple' => true,
                'label' => 'Recherche par état du devis',
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchDevisData::class,
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