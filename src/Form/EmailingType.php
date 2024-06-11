<?php

namespace App\Form;

use App\Model\EmailingData;
use App\Model\SearchDevisData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailingType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email du client',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Adresse email du client...',
                    'value' => $options["data"]->email,
                 
                ]
            ])
            ->add('object', TextType::class, [
                'label' => 'Objet du mail',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Objet du mail...',
                    'value' => $options["data"]->object,  
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Corps du mail',
                'empty_data' => '',
                'required' => false,
                'attr' => [              
                    'placeholder' => 'Corps du mail...',
                    'rows' => 6,
                    'value' =>  nl2br($options["data"]->content),  
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmailingData::class,
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