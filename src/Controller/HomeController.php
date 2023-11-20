<?php

namespace App\Controller;

use App\Repository\DevisRepository;
use App\Repository\FactureRepository;
use App\Repository\ParticulierRepository;
use App\Repository\ProfessionnelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ParticulierRepository $particulierRepository, 
                          ProfessionnelRepository $professionnelRepository, 
                          DevisRepository $devisRepository, 
                          FactureRepository $factureRepository,
                          MailerInterface $mailer): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $email = (new Email())
             ->from('admin@placocompta.fr')
             ->to('jordan.chariot@gmail.com')
             ->subject('test')
             ->text('test email')
             ->html('test');

        $mailer->send($email);


        $devis = $devisRepository->findOneBy([], ["DateDevis" => "DESC"]);
        $facture = $factureRepository->findOneBy([], ["DateFacture" => "DESC"]);
        $particulier = $particulierRepository->findOneBy([], ["createdAt" => "DESC"]);
        $professionnel = $professionnelRepository->findOneBy([], ["createdAt" => "DESC"]);

        $anneeEnCours = date("Y");

        $lstdevis = $devisRepository->findBy(["Plusutilise" => false]);
        $nbDevis = 0;
        $nbDevisTransformer = 0;
        foreach ($lstdevis as $devisTmp){
            if ($anneeEnCours == $devisTmp->getDateDevis()->format("Y")){
                $nbDevis++;
            }

            if ($devisTmp->getFacture() && !$devisTmp->getFacture()->isPlusutilise()){
                $nbDevisTransformer++; 
            }
        }

        $factures = $factureRepository->findBy(["Plusutilise" => false]);
        $totalTVA = 0;
        $totalMontantFactureHT = 0;
        $totalMontantFactureTTC = 0;
        $nbFacture = 0;
        foreach ($factures as $factureTmp){
            if ($anneeEnCours == $factureTmp->getDateFacture()->format("Y")){
                $nbFacture++;
                $totalMontantFactureHT = $totalMontantFactureHT + $factureTmp->getPrixHT();
                $totalMontantFactureTTC = $totalMontantFactureTTC + $factureTmp->getPrixTTC();
            }
        }

        $totalTVA = $totalMontantFactureTTC - $totalMontantFactureHT;

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'devis' => $devis,
            'facture' => $facture,
            'particulier' => $particulier,
            'professionnel' => $professionnel,
            'nbDevis' => $nbDevis,
            'nbFacture' => $nbFacture,
            'nbDevisTransformer' => $nbDevisTransformer,
            'totalMontantFacture' => $totalMontantFactureTTC,
            'totalTVA' => $totalTVA
        ]);
    }
}
