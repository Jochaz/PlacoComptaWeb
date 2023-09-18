<?php

namespace App\Controller;

use App\Form\AdresseChantierType;
use App\Repository\AdresseDocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdresseController extends AbstractController
{
    #[Route('/adressechantier/{id}', name: 'app_adresse_chantier')]
    public function index(string $id, Request $request, AdresseDocumentRepository $adresseDocumentRepository): Response
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
            return $this->redirectToRoute('app_adresse_chantier', ["id" => $adresseChantier->getId()]);     
        }


        return $this->render('adresse/index.html.twig', [
            'adresseChantier' => $adresseChantier,
            'form' => $form,
            'controller_name' => 'AdresseController',
        ]);
    }
}
