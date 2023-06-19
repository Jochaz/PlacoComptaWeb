<?php

namespace App\Form;

use App\Entity\Particulier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', options:[
                'label' => 'Nom du client'
            ])
            ->add('prenom', options:[
                'label' => 'Prénom du client'
            ])
            ->add('adresseemail1', EmailType::class, options:[
                'label' => 'Adresse email n°1 du client',
                'invalid_message' => 'L\'adresse email n°1 saisie est incorrect.',
            ])
            ->add('adresseemail2', EmailType::class, options:[
                'label' => 'Adresse email n°2 du client',
                'required'          => false,
                'invalid_message' => 'L\'adresse email n°2 saisie est incorrect.',
            ])
            ->add('adresseemail3', EmailType::class, options:[
                'label' => 'Adresse email n°3 du client',
                'required'          => false,
                'invalid_message' => 'L\'adresse email n°3 saisie est incorrect.',
            ])
            ->add('numerotelephoneportable1', TelType::class, options:[
                'label' => 'Numéro de téléphone portable n°1 du client'
            ])
            ->add('numerotelephoneportable2', TelType::class, options:[
                'required'          => false,
                'label' => 'Numéro de téléphone portable n°2 du client'
            ])
            ->add('numerotelephonefixe1', TelType::class, options:[
                'required'          => false,
                'label' => 'Numéro de téléphone fixe n°1 du client'
            ])
            ->add('numerotelephonefixe2', TelType::class, options:[
                'required'          => false,
                'label' => 'Numéro de téléphone fixe n°2 du client'
            ])
            ->add('commentaire', TextareaType::class, options:[
                'label' => 'Commentaire sur le client',
                'required'          => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Particulier::class,
        ]);
    }
}
