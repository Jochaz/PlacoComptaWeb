<?php

namespace App\Controller;

use App\Repository\FactureRepository;
use App\Repository\ModeReglementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EcheancierController extends AbstractController
{
    #[Route('/echeancier/{id}', name: 'app_echeancier_facture')]
    public function gestionEcheancier(string $id, Request $request, FactureRepository $factureRepository, ModeReglementRepository $modeReglementRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $facture = $factureRepository->findOneBy(["id" => $id]);
        if (!$facture){
            return $this->redirectToRoute('app_facture');
        }

        $modeReglements = $modeReglementRepository->findAll();
        if ($request->getMethod() == Request::METHOD_POST){
            if (count($facture->getEcheances()) != 0)
            {
                $IsRegle = false;
                if ($request->request->get('chk_echeance1') == "on"){
                    $IsRegle = true;
                }
                $facture->getEcheances()[0]->setIsRegle($IsRegle);
                $modeReglement = $modeReglementRepository->find($request->request->get('mode_reglement_ech1'));
                if ($modeReglement){
                    $facture->getEcheances()[0]->setModeReglement($modeReglement);
                }

                $factureRepository->save($facture, true);
            }   

            return $this->render('echeancier/facture.html.twig', [
                'controller_name' => 'EcheancierController',
                'facture' => $facture,
                'modereglements' => $modeReglements
            ]);
        }

        return $this->render('echeancier/facture.html.twig', [
            'controller_name' => 'EcheancierController',
            'facture' => $facture,
            'modereglements' => $modeReglements
        ]);
    }
}
