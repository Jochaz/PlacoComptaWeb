<?php

namespace App\Controller;

use App\Entity\AdresseDocument;
use App\Form\AdresseChantierType;
use App\Form\AdresseFacturationType;
use App\Repository\AdresseDocumentRepository;
use App\Repository\AdresseFacturationRepository;
use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdresseController extends AbstractController
{
    #[Route('/adressechantier/{id}', name: 'app_adresse_chantier')]
    public function index(string $id, Request $request, AdresseDocumentRepository $adresseDocumentRepository, DevisRepository $devisRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $adresseChantier = $adresseDocumentRepository->find($id);
        if (!$adresseChantier) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(AdresseChantierType::class, $adresseChantier);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $adresseDocumentRepository->save($adresseChantier, true);
            $devis = $devisRepository->findByIdAdresseFacturation($adresseChantier->getId());
            if ($devis)  {
                return $this->redirectToRoute('app_devis_detail', ["id" => $devis->getId()]);
            } else {
                return $this->redirectToRoute('app_adresse_chantier', ["id" => $adresseChantier->getId()]);
            }   
            return $this->redirectToRoute('app_adresse_chantier', ["id" => $adresseChantier->getId()]);     
        }

        dump($adresseChantier);
        return $this->render('adresse/index.html.twig', [
            'adresseChantier' => $adresseChantier,
            'form' => $form,
            'controller_name' => 'AdresseController',
        ]);
    }

    #[Route('/adressefacturation/{id}', name: 'app_adresse_facturation')]
    public function indexFacturation(string $id, Request $request, AdresseFacturationRepository $adresseFacturationRepository, DevisRepository $devisRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $adresseFacturation = $adresseFacturationRepository->find($id);
        if (!$adresseFacturation) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(AdresseFacturationType::class, $adresseFacturation);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $adresseFacturationRepository->save($adresseFacturation, true);
            $devis = $devisRepository->findByIdAdresseFacturation($adresseFacturation->getId());
            if ($devis)  {
                return $this->redirectToRoute('app_devis_detail', ["id" => $devis->getId()]);
            } else {
                return $this->redirectToRoute('app_adresse_facturation', ["id" => $adresseFacturation->getId()]);
            }    
        }

        return $this->render('adresse/facturation.html.twig', [
            'adresseChantier' => $adresseFacturation,
            'form' => $form,
            'controller_name' => 'AdresseController',
        ]);
    }
}
