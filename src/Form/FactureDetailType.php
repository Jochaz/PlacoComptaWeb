<?php

namespace App\Form;

use App\Entity\Facture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NumFacture', options: [
                'label' => 'Numéro de la facture',
                'required' => false,
                'disabled' => true
            ])
            ->add('DateFacture', options: [
                'label' => 'Date de création de la facture',
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('DatePaiementFacture', DateType::class, options: [
                'label' => 'Date du règlement de la facture',
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
                'label' => 'Remise sur le prix totale de la facture (€)',
                'required' => false,
                'empty_data' => '0',
            ])
            ->add('PrixHT', options: [
                'label' => 'Prix de la facture hors taxe (€)',
                'required' => false,
                'disabled' => true,
            ])
            ->add('PrixTTC', options: [
                'label' => 'Prix de la facture toutes taxes comprises (€)',
                'required' => false,
                'disabled' => true,
            ])
            ->add('TVAAutoliquidation', options: [
                'label' => 'TVA due par le preneur assujetti autoliquidation application article 242 nonies A, I-13° annexe au CGI'
            ])
            ->add('isSousTotaux', options:[
                'label' => 'Afficher les sous-totaux des modèles'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
