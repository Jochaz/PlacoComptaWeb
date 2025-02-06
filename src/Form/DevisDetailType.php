<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NumDevis', options: [
                'label' => 'Numéro du devis',
                'required' => false,
                'disabled' => true
            ])
            ->add('DateDevis', options: [
                'label' => 'Date de création du devis',
                'required' => false,
                'disabled' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('DateSignature', DateType::class, options: [
                'label' => 'Date de la signature du devis',
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('NumDossier', options: [
                'label' => 'Numéro de dossier',
                'required' => false
            ])
            ->add('Objet', options: [
                'label' => 'Objet',
                'required' => false
            ])
            ->add('Remise', options: [
                'label' => 'Remise sur le prix totale du devis (€)',
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('PrixHT', options: [
                'label' => 'Prix du devis hors taxe (€)',
                'required' => false,
                'disabled' => true,
            ])
            ->add('PrixTTC', options: [
                'label' => 'Prix du devis toutes taxes comprises (€)',
                'required' => false,
                'disabled' => true,
            ])
            ->add('TVAAutoliquidation', options: [
                'label' => 'TVA due par le preneur assujetti autoliquidation application article 242 nonies A, I-13° annexe au CGI'
            ])
            ->add('isSousTotaux', options:[
                'label' => 'Afficher les sous-totaux des modèles'
            ])
            ->add('DureeValidite', options:[
                'label' => 'Durée de validité du devis (en mois)'                 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
