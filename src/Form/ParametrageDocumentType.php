<?php

namespace App\Form;

use App\Entity\ParametrageDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParametrageDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Prefixe', options:[
                'label' => 'Préfixe'
            ])
            ->add('AnneeEnCours', options:[
                'label' => 'Afficher l\'année en cours'
            ])
            ->add('NumeroAGenerer', options:[
                'label' => 'Prochain numéro à générer'
            ])
            ->add('NombreCaractereTotal', options:[
                'label' => 'Nombre de caractère total'
            ])
            ->add('CompletionAvecZero', options:[
                'label' => 'Completer le reste avec des zéros'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParametrageDocument::class,
        ]);
    }
}
