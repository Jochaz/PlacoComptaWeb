<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\Particulier;
use App\Entity\Professionnel;
use App\Repository\ParticulierRepository;
use App\Repository\ProfessionnelRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisInfoGeneraleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NumDevis' , options:[
                'label' => 'Numéro du devis',
                'required' => false,
                'disabled' => true
            ])
            ->add('DateDevis', options:[
                'label' => 'Date de création du devis',
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('DateSignature', DateType::class, options:[
                'label' => 'Date de la signature du devis',
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('NumDossier', options:[
                'label' => 'Numéro de dossier',
                'required' => false
            ])
            ->add('Objet', options:[
                'label' => 'Objet',
                'required' => false
            ])
            ->add('TVAAutoliquidation', options:[
                'label' => 'TVA due par le preneur assujetti autoliquidation application article 242 nonies A, I-13° annexe au CGI'
            ])
            ->add('Particulier', options:[
                'label' => 'Client (particulier)',
                'class' => Particulier::class,  
                'attr' => ['required' => true],
                'query_builder' => function (ParticulierRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.actif = true')
                        ->orderBy('p.nom, p.prenom', 'ASC');
                },
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('Professionnel',  options:[
                'label' => 'Client (professionnel)',
                'class' => Professionnel::class,
                'attr' => ['required' => false],
                'query_builder' => function (ProfessionnelRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.actif = true')
                        ->orderBy('p.nomsociete', 'ASC');
                },
                'multiple' => false,
                'expanded' => false
            ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
