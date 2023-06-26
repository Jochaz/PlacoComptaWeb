<?php

namespace App\Form;

use App\Entity\EnteteDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnteteDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Ligne1Gauche', options:[
                'label' => 'Ligne 1 (Gauche)'
            ])
            ->add('Ligne2Gauche', options:[
                'label' => 'Ligne 2 (Gauche)'
            ])
            ->add('Ligne3Gauche', options:[
                'label' => 'Ligne 3 (Gauche)'
            ])
            ->add('Ligne1Droite', options:[
                'label' => 'Ligne 1 (Droite)'
            ])
            ->add('Ligne2Droite', options:[
                'label' => 'Ligne 2 (Droite)'
            ])
            ->add('Ligne3Droite', options:[
                'label' => 'Ligne 3 (Droite)'
            ])
            ->add('Ligne4Gauche', options:[
                'label' => 'Ligne 4 (Gauche)'
            ])
            ->add('Ligne4Droite', options:[
                'label' => 'Ligne 4 (Droite)'
            ])
            ->add('VilleFaitA', options:[
                'label' => 'Ville (fait à)'
            ])
            ->add('NumeroTelFixe', options:[
                'label' => 'Numéro de téléphone (Fixe)'
            ])
            ->add('NumeroFax', options:[
                'label' => 'Numéro de Fax'
            ])
            ->add('NumeroTelPortable', options:[
                'label' => 'Numéro de téléphone (portable)'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EnteteDocument::class,
        ]);
    }
}
