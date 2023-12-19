<?php

namespace App\Controller;

use App\Entity\Acompte;
use App\Entity\AdresseDocument;
use App\Entity\AdresseFacturation;
use App\Entity\Devis;
use App\Entity\Echeance;
use App\Entity\Facture;
use App\Entity\LigneDevis;
use App\Entity\LigneFacture;
use App\Form\AcompteType;
use App\Form\AdresseChantierType;
use App\Form\AdresseFacturationType;
use App\Form\DevisDetailType;
use App\Form\DevisInfoGeneraleType;
use App\Form\DevisRemiseType;
use App\Form\SearchDevisType;
use App\Model\SearchDevisData;
use App\Repository\AdresseDocumentRepository;
use App\Repository\AdresseFacturationRepository;
use App\Repository\DevisRepository;
use App\Repository\EcheanceRepository;
use App\Repository\EnteteDocumentRepository;
use App\Repository\FactureRepository;
use App\Repository\LigneDevisRepository;
use App\Repository\MateriauxRepository;
use App\Repository\ModelePieceRepository;
use App\Repository\ModeReglementRepository;
use App\Repository\ParametrageDevisRepository;
use App\Repository\ParametrageFactureRepository;
use App\Repository\TVARepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class DevisController extends AbstractController
{
    #[Route('/quote', name: 'app_devis')]
    public function index(DevisRepository $devisRepository, 
                          ModelePieceRepository $modelePieceRepository,
                          Request $request, 
                          PaginatorInterface $paginator 
                          ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $peutCreerDevis = true;
        $modeles = $modelePieceRepository->findAll();
        if (count($modeles) == 0){
            $peutCreerDevis = false;
        }

        $searchData = new SearchDevisData();
        $form = $this->createForm(SearchDevisType::class, $searchData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $pagination = $paginator->paginate(
                $devisRepository->findBySearch($searchData),
                $request->query->get('page', 1),
                10
            );
    
            return $this->render('devis/index.html.twig', [
                'pagination' => $pagination,
                'form' => $form,
                'peutCreerDevis' => $peutCreerDevis
            ]);
        }

        $pagination = $paginator->paginate(
            $devisRepository->paginationQuery(),
            $request->query->get('page', 1),
            10
        );

        return $this->render('devis/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'peutCreerDevis' => $peutCreerDevis
        ]);
    }

    #[Route('/quote/add/info', name: 'app_devis_add_info')]
    public function addInfo(Request $request, DevisRepository $devisRepository, 
                            ParametrageDevisRepository $parametrageDevisRepository, ModelePieceRepository $modelePieceRepository): Response
    {
        function insertToString(string $mainstr,string $insertstr,int $index):string
        {
            return substr($mainstr, 0, $index) . $insertstr . substr($mainstr, $index);
        }

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $modeles = $modelePieceRepository->findAll();
        if ($modeles && count($modeles) == 0){
            return $this->redirectToRoute('app_devis');
        }

        $parametrageDevis = $parametrageDevisRepository->findOneBy(['TypeDocument' => 'Devis']);

        $devis = new Devis();

        if ($parametrageDevis) {
            $numDevis = $parametrageDevis->getPrefixe();
            if ($parametrageDevis->isAnneeEnCours()){
                $numDevis = $numDevis.date("Y");
            }

            $numDevis = $numDevis.$parametrageDevis->getNumeroAGenerer();

            if ($parametrageDevis->isCompletionAvecZero() && 
                strlen($numDevis) < $parametrageDevis->getNombreCaractereTotal()){
                $nbZeroAMettre = $parametrageDevis->getNombreCaractereTotal() - strlen($numDevis);
                
                $lesZeros = '';
                for ($i = 1; $i <= $nbZeroAMettre; $i++){
                    $lesZeros = $lesZeros.'0';
                }                
                $numDevis = insertToString($numDevis, $lesZeros, (strlen($numDevis) - strlen($parametrageDevis->getNumeroAGenerer())));
            }

            $devis->setNumDevis($numDevis);
            $devis->setDateDevis(new \DateTime());
        }

        $form = $this->createForm(DevisInfoGeneraleType::class, $devis);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            //Si pro
            if($request->request->get("SwitchTypeClient")){
                $devis->setParticulier(null);
            } else {
                $devis->setProfessionnel(null);
            }
            
            $devis->setPlusutilise(false);
            $devisRepository->save($devis, true);

            $parametrageDevis->setNumeroAGenerer($parametrageDevis->getNumeroAGenerer() + 1);
            $parametrageDevisRepository->save($parametrageDevis, true);

            return $this->redirectToRoute('app_devis_add_adresse_devis', ["devis" => serialize($devis)]);
        }

        return $this->render('devis/add/info.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/quote/add/adressedevis', name: 'app_devis_add_adresse_devis')]
    public function addAdresseChantier(Request $request, AdresseDocumentRepository $adresseChantierRepository, 
    AdresseFacturationRepository $adresseFacturationRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$request->query->get('devis')){
            return $this->redirectToRoute('app_devis_add_info');
        }
        $devis = unserialize($request->query->get('devis'));
        $adresseDevis = new AdresseDocument();

        $form= $this->createForm(AdresseChantierType::class, $adresseDevis);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $devis->setAdresseChantier($adresseDevis);
            $adresseChantierRepository->save($adresseDevis, true);
 
            if ($request->request->get('copieAdresseChantierSurFacturation')) {
                $adresseFacturation = new AdresseFacturation();
                $adresseFacturation->setLigne1($adresseDevis->getLigne1());
                $adresseFacturation->setLigne2($adresseDevis->getLigne2());
                $adresseFacturation->setLigne3($adresseDevis->getLigne3());
                $adresseFacturation->setVille($adresseDevis->getVille());
                $adresseFacturation->setCP($adresseDevis->getCP());
                $adresseFacturation->setBoitePostale($adresseDevis->getBoitePostale());

                $devis->setAdresseFacturation($adresseFacturation);  
                $adresseFacturationRepository->save($adresseFacturation, true);  
                return $this->redirectToRoute('app_devis_add_ligne', ["devis" => serialize($devis)]);     
            } else {
                return $this->redirectToRoute('app_devis_add_adresse_facturation_devis', ["devis" => serialize($devis)]);
            }
            
        }

        return $this->render('devis/add/adresse_chantier.html.twig', [
            'form' => $form->createView(),
            'modif' => false,
        ]);
    }

    #[Route('/quote/add/adressefacturation', name: 'app_devis_add_adresse_facturation_devis')]
    public function addAdresseFacturation(Request $request, AdresseFacturationRepository $adresseFacturationRepository, DevisRepository $devisRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$request->query->get('devis')){
            return $this->redirectToRoute('app_devis_add_info');
        }
        $devis = unserialize($request->query->get('devis'));
        $adresse = new AdresseFacturation();

        $form= $this->createForm(AdresseFacturationType::class, $adresse);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $devis->setAdresseFacturation($adresse);
            $adresseFacturationRepository->save($adresse, true); 
            $devisRepository->save($devis, true); 
            return $this->redirectToRoute('app_devis_add_ligne', ["devis" => serialize($devis)]);     
        }

        return $this->render('devis/add/adresse_chantier.html.twig', [
            'form' => $form->createView(),
            'modif' => false,
        ]);
    }

    #[Route('/quote/add/ligne', name: 'app_devis_add_ligne')]
    public function addDevisLigne(Request $request, 
    ModelePieceRepository $modelePieceRepository, 
    TVARepository $tVARepository, 
    DevisRepository $devisRepository,

    MateriauxRepository $materiauxRepository,
    AdresseDocumentRepository $adresseChantierRepository,
    AdresseFacturationRepository $adresseFacturationRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $devis = unserialize($request->query->get('devis'));
        if (!$devis && $request->getMethod() != Request::METHOD_POST){
            return $this->redirectToRoute('app_devis_add_info');
        }

        if ($request->getMethod() == Request::METHOD_POST){
            $devis = $devisRepository->findWithJoin(intval($request->request->get("devis")));
            if ($request->request->get('adresseC')){
           
                $AdresseChantier = $adresseChantierRepository->findOneBy(["id" => $request->request->get('adresseC')]);
                $devis->setAdresseChantier($AdresseChantier);
            }

            if ($request->request->get('adresseF')){
             
                $AdresseFacturation = $adresseFacturationRepository->findOneBy(["id" => $request->request->get('adresseF')]);
                $devis->setAdresseFacturation($AdresseFacturation);
            }

            //On va parcourir toutes les données
            foreach ($request->request as $key => $value){
             
                if (str_contains($key, 'checked_materiaux_')) {
                    $params = explode('_', $key);
                    //0 et 1 - nom / 2 - id modele / 3 - id materiaux
                    $identifiant = "_".$params[2]."_".$params[3];
                    $materiaux = $materiauxRepository->find(["id" => $params[3]]);

                    $ligneDevis = new LigneDevis();
                  

                    if ($request->request->get('des'.$identifiant)){
                        $ligneDevis->setDesignation($request->request->get('des'.$identifiant));
                    } else {
                        $ligneDevis->setDesignation($materiaux->getDesignation());
                    }
                    
                    if ($request->request->get('pu'.$identifiant)){
                        $ligneDevis->setPrixUnitaire(floatval($request->request->get('pu'.$identifiant)));
                    } else {                   
                        $ligneDevis->setPrixUnitaire($materiaux->getPrixUnitaire());
                    }

                    if ($request->request->get('qte'.$identifiant)){
                        $ligneDevis->setQte($request->request->get('qte'.$identifiant));
                    } else {
                        $ligneDevis->setQte(1);
                    }

                    if ($request->request->get('remise'.$identifiant)){
                        $ligneDevis->setRemise(floatval($request->request->get('remise'.$identifiant)));
                    } else {
                        $ligneDevis->setRemise(0);
                    }

                    if ($request->request->get('tva'.$identifiant)){
                        $ligneDevis->setTVA($tVARepository->findOneBy(["id" => $request->request->get('tva'.$identifiant)]));
                    }
                    $ligneDevis->setMateriaux($materiaux);
                    $devis->addLigneDevi($ligneDevis);
                }
            }
            $devisRepository->save($devis, true);
            return $this->redirectToRoute('app_devis_add_recap', ["devis" => serialize($devis)]);
        } 
        $modelesPiece = $modelePieceRepository->findByUse();
        $tvas = $tVARepository->findAll();

        return $this->render('devis/add/lignes.html.twig', [
            'modelesPiece' =>$modelesPiece,
            'tvas' => $tvas,
            'devis' => $devis
        ]);

    }

    #[Route('/quote/add/recap', name: 'app_devis_add_recap')]
    public function addRecapDevis(Request $request, DevisRepository $devisRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$request->query->get('devis')){
            return $this->redirectToRoute('app_devis_add_info');
        }
      
        $devis = unserialize($request->query->get('devis'));
        $devis = $devisRepository->findOneBy(["id" => $devis->getId()]);
        $form= $this->createForm(DevisRemiseType::class, $devis);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $devis->setPrixHT($devis->getPrixHT());
            $devis->setPrixTTC($devis->getPrixTTC());
            $devisRepository->save($devis, true);
            return $this->redirectToRoute('app_devis');     
        }

        return $this->render('devis/add/recap.html.twig', [
            'devis' => $devis,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/quote/detail/{id}', name: 'app_devis_detail')]
    public function quoteDetail(string $id, Request $request, DevisRepository $devisRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $devis = $devisRepository->findOneBy(["id" => $id]);

        if (!$devis){
            return $this->redirectToRoute('app_devis');
        }                 
        
        if (!$devis->getRemise()){
            $devis->setRemise(0);
        }
       
        $peutPasModifier = "0";
        if ($devis->getFacture()){
            if (!$devis->getFacture()->peutModifierDocument()) {
                $peutPasModifier = "1";
            };
        }
        $form = $this->createForm(DevisDetailType::class, $devis, ["disabled" => $peutPasModifier]);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $devis->setPrixHT($devis->getPrixHT());
            $devis->setPrixTTC($devis->getPrixTTC());
            $devisRepository->save($devis, true);
            return $this->redirectToRoute('app_devis_detail', ["id" => $devis->getId()]);     
        }

        return $this->render('devis/detail.html.twig', [
            'devis' => $devis,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/quote/updatecontent/{id}', name: 'app_devis_contenu')]
    public function quotedContent(string $id, Request $request, DevisRepository $devisRepository, LigneDevisRepository $ligneDevisRepository, MateriauxRepository $materiauxRepository, TVARepository $tVARepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $devis = $devisRepository->findOneBy(["id" => $id]);
        $tvas = $tVARepository->findAll();
        $ligneAdd = $materiauxRepository->findByMateriauxManquantDevis($devis->getId());

        if (!$devis){
            return $this->redirectToRoute('app_devis');
        }

      
        if ($request->getMethod() == Request::METHOD_POST){
            //Si on est sur un ajout de materiaux    
            if ($request->request->get('materiaux_id'))
            {   
                $materiaux = $materiauxRepository->find($request->request->get('materiaux_id'));
                if ($materiaux){                                               
                    $tva = $tVARepository->find($request->request->get('tva_add'));
                
                    $ligneDevis = new LigneDevis();
                    $ligneDevis->setMateriaux($materiaux);
                    $ligneDevis->setTVA($tva);
                    $ligneDevis->setDesignation($request->request->get('des_add'));
                    $ligneDevis->setQte($request->request->get('qte_add'));
                    if ($request->request->get('remise_add')) {
                        $ligneDevis->setRemise($request->request->get('remise_add'));
                    } else {
                        $ligneDevis->setRemise(0);
                    }                   
                    $ligneDevis->setPrixUnitaire($request->request->get('pu_add'));
                    $devis->addLigneDevi($ligneDevis);
                    
                    $this->addFlash('success', 'Devis modifié avec succès');
                } else {
                    $this->addFlash('danger', 'Devis non modifié : Matériaux inconnu');    
                }
            } else {
                foreach ($request->request as $key => $value){
                    if (str_contains($key, 'ligne_')){
                        $identifiant = $value;
                        foreach($devis->getLigneDevis() as $ligne){
                            if ($ligne->getId() == $identifiant){
                                if ($request->request->get('des_'.$identifiant)){
                                    $ligne->setDesignation($request->request->get('des_'.$identifiant));
                                } 

                                if ($request->request->get('qte_'.$identifiant)){
                                    $ligne->setQte($request->request->get('qte_'.$identifiant));
                                } 

                                if ($request->request->get('pu_'.$identifiant)){
                                    $ligne->setPrixUnitaire($request->request->get('pu_'.$identifiant));
                                } 

                                if ($request->request->get('remise_'.$identifiant)){
                                    $ligne->setRemise($request->request->get('remise_'.$identifiant));
                                }
                                
                                if ($request->request->get('tva_'.$identifiant)){
                                    $ligne->setTVA($tVARepository->findOneBy(["id" => $request->request->get('tva_'.$identifiant)]));
                                }
                                
                                $ligneDevisRepository->save($ligne, true);
                            }
                        }
                    }
                }
                
                $this->addFlash('success', 'Devis modifié avec succès');
            }
            $devis->setPrixHT($devis->getPrixHT());
            $devis->setPrixTTC($devis->getPrixTTC());
            $devisRepository->save($devis, true);
    
            return $this->redirectToRoute('app_devis_contenu', ["id" => $devis->getId()]);     
        }

        return $this->render('devis/contenu.html.twig', [
            'devis' => $devis, 
            'tvas' => $tvas,
            'ligneAdd' => $ligneAdd
        ]);
    }

    #[Route('/quote/disable/{id}', name: 'app_devis_disable')]
    public function quoteDisable(string $id, DevisRepository $devisRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $devis = $devisRepository->findOneBy(["id" => $id]);

        if (!$devis){
            return $this->redirectToRoute('app_devis');
        }
        
        $devis->setPlusutilise(true);
        $devisRepository->save($devis, true);

        $this->addFlash('success', 'Devis désactivé avec succès');    
        return $this->redirectToRoute('app_devis');   
    }

    #[Route('/quote/acompte/{id}', name: 'app_devis_acompte')]
    public function quoteAcompte(string $id, DevisRepository $devisRepository, Request $request, ModeReglementRepository $modeReglementRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $devis = $devisRepository->findOneBy(["id" => $id]);

        if (!$devis){
            return $this->redirectToRoute('app_devis');
        }

        if ($devis->getAcompte()){
            $acompte = $devis->getAcompte();
        } else {
            $acompte = new Acompte();
        }

        $acompte->setMontant(round((($devis->getPrixTTC() / 10)), 2));
        $modesReglement = $modeReglementRepository->findAll(); 
        
        $form = $this->createForm(AcompteType::class, $acompte);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $devis->setAcompte($acompte);
            $devisRepository->save($devis, true);
            $this->addFlash('success', 'Acompte géré avec succès'); 
            return $this->redirectToRoute('app_devis_detail', ["id" => $devis->getId()]);     
        }

   
        return $this->render('devis/acompte.html.twig', [
            'devis' => $devis, 
            'form' => $form,
            'modesReglement' => $modesReglement,
        ]); 
    }

    #[Route('/quote/delete/ligne/{id}', name: 'app_devis_disable')]
    public function quoteDisableLine(string $id, LigneDevisRepository $LigneDevisRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $ligne = $LigneDevisRepository->findOneBy(["id" => $id]);

        if (!$ligne){
            return $this->redirectToRoute('app_devis');
        }
        
        $LigneDevisRepository->remove($ligne, true);
        
        $this->addFlash('success', 'Ligne de devis supprimé avec succès');    
        return $this->redirectToRoute('app_devis_contenu', ["id" => $ligne->getDevis()->getId()]);      
    }

    #[Route('/quote/generatePDF/{id}', name: 'app_devis_PDF')]
    public function quoteGeneratePdf(string $id, DevisRepository $devisRepository, EnteteDocumentRepository $enteteDocumentRepository, LigneDevisRepository $ligneDevisRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $devis = $devisRepository->findOneBy(["id" => $id]);

        if (!$devis){
            return $this->redirectToRoute('app_devis');
        }

        $entete = $enteteDocumentRepository->findAll()[0];
        $LigneAdresseChantier = $devis->getAdresseChantier()->getLigne1();
        if (!is_null($devis->getAdresseChantier()->getLigne2())){
            $LigneAdresseChantier = $LigneAdresseChantier.'\n'.$devis->getAdresseChantier()->getLigne2();
        }
        if (!is_null($devis->getAdresseChantier()->getLigne3())){
            $LigneAdresseChantier = $LigneAdresseChantier.'\n'.$devis->getAdresseChantier()->getLigne3();
        }
        $LigneAdresseFacturation = $devis->getAdresseFacturation()->getLigne1();
        if (!is_null($devis->getAdresseFacturation()->getLigne2())){
            $LigneAdresseFacturation = $LigneAdresseFacturation.'\n'.$devis->getAdresseFacturation()->getLigne2();
        }
        if (!is_null($devis->getAdresseFacturation()->getLigne3())){
            $LigneAdresseFacturation = $LigneAdresseFacturation.'\n'.$devis->getAdresseFacturation()->getLigne3();
        }
        if($devis->getParticulier()){
            $nomprenom = $devis->getParticulier()->getNom()." ".$devis->getParticulier()->getPrenom();
        } else if ($devis->getProfessionnel()){
            $nomprenom = $devis->getProfessionnel()->getNomsociete();
        }

        $lignes = $ligneDevisRepository->findByIdDevisAndOrderByCategorie($devis->getId());

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

          // Retrieve the HTML generated in our twig file
          $html = $this->renderView('pdf/devis.html.twig', [
            'title' => "Devis N°".$devis->getNumDevis(),

            'Ligne1Gauche' => $entete->getLigne1Gauche(),
            'Ligne2Gauche' => $entete->getLigne2Gauche(),
            'Ligne3Gauche' => $entete->getLigne3Gauche(),
            'Ligne4Gauche' => $entete->getLigne4Gauche(),

            'Ligne1Droite' => $entete->getLigne1Droite(),
            'Ligne2Droite' => $entete->getLigne2Droite(),
            'Ligne3Droite' => $entete->getLigne3Droite(),
            'Ligne4Droite' => $entete->getLigne4Droite(),

            'TelFixe' => $entete->getNumeroTelFixe(),
            'TelFax' => $entete->getNumeroFax(),
            'TelPort' => $entete->getNumeroTelPortable(),

            'NumDevis' => $devis->getNumDevis(),

            'Ville' => $entete->getVilleFaitA(),
            'DateDevis' => $devis->getDateDevis()->format('d-m-Y'),

            'NomPrenom' => $nomprenom,
            'LigneAdresseClient' => $LigneAdresseFacturation,
            
            'VilleCP' => $devis->getAdresseFacturation()->getCP().' '.$devis->getAdresseFacturation()->getVille(),

            'LigneAdresseChantier' => $LigneAdresseChantier,
            'CPVilleAdresseChantier' => $devis->getAdresseChantier()->getCP().' '.$devis->getAdresseChantier()->getVille(),

            'MontantHT' => $devis->getPrixHT(),
            'MontantTTC' => $devis->getPrixTTC(),
            'TotalTVA' => $devis->getPrixTTC() - $devis->getPrixHT(),

            // 'lignes' => $devis->getLigneDevis(),
            'lignes' => $lignes,
            'devis' => $devis
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        ob_end_clean();
        $dompdf->stream($devis->getNumDevis().".pdf", [
            "Attachment" => 0
        ]);
        return new Response("The PDF file has been succesfully generated !");
    }

    #[Route('/quote/transform/{id}', name: 'app_devis_transform')]
    public function transformDevis(string $id, Request $request, DevisRepository $devisRepository,
    FactureRepository $factureRepository,
    ParametrageFactureRepository $parametrageFactureRepository,
    AdresseDocumentRepository $adresseDocumentRepository,
    AdresseFacturationRepository $adresseFacturationRepository,
    EcheanceRepository $echeanceRepository,
    ModeReglementRepository $modeReglementRepository): Response
    {

        function insertToStr(string $mainstr,string $insertstr,int $index):string
        {
            return substr($mainstr, 0, $index) . $insertstr . substr($mainstr, $index);
        }
        

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $devis = $devisRepository->findOneBy(["id" => $id]);
        if (!$devis) {
            return $this->redirectToRoute('app_devis');
        }

        if ($devis->getFacture()){
            return $this->redirectToRoute('app_facture_detail', ["id" => $devis->getFacture()->getId()]);
        }

        $facture = new Facture();
        $facture->setObjet($devis->getObjet());
        $facture->setNumDossier($devis->getNumDossier());
        $facture->setTVAAutoliquidation($devis->isTVAAutoliquidation());
        $facture->setDateFacture(new \DateTime());

        $adresseChantier = new AdresseDocument();
        $adresseChantier->setLigne1($devis->getAdresseChantier()->getLigne1());
        $adresseChantier->setLigne2($devis->getAdresseChantier()->getLigne2());
        $adresseChantier->setLigne3($devis->getAdresseChantier()->getLigne3());
        $adresseChantier->setCP($devis->getAdresseChantier()->getCP());
        $adresseChantier->setVille($devis->getAdresseChantier()->getVille());
        $adresseChantier->setBoitePostale($devis->getAdresseChantier()->getBoitePostale());
        $adresseDocumentRepository->save($adresseChantier, true);

        $adresseFacturation = new AdresseFacturation();
        $adresseFacturation->setLigne1($devis->getAdresseFacturation()->getLigne1());
        $adresseFacturation->setLigne2($devis->getAdresseFacturation()->getLigne2());
        $adresseFacturation->setLigne3($devis->getAdresseFacturation()->getLigne3());
        $adresseFacturation->setCP($devis->getAdresseFacturation()->getCP());
        $adresseFacturation->setVille($devis->getAdresseFacturation()->getVille());
        $adresseFacturation->setBoitePostale($devis->getAdresseFacturation()->getBoitePostale());
        $adresseFacturationRepository->save($adresseFacturation, true);

        $facture->setAdresseChantier($adresseChantier);
        $facture->setAdresseFacturation($adresseFacturation);

        $facture->setParticulier($devis->getParticulier());
        $facture->setProfessionnel($devis->getProfessionnel());

        foreach ($devis->getLigneDevis() as $ligneDevis){
            $ligneFacture = new LigneFacture();
            $ligneFacture->setMateriaux($ligneDevis->getMateriaux());
            $ligneFacture->setDesignation($ligneDevis->getDesignation());
            $ligneFacture->setPrixUnitaire($ligneDevis->getPrixUnitaire());
            $ligneFacture->setQte($ligneDevis->getQte());
            $ligneFacture->setRemise($ligneDevis->getRemise());
            $ligneFacture->setTVA($ligneDevis->getTVA());
            $ligneFacture->setFacture($facture);
            $facture->addLigneFacture($ligneFacture);
        }

        $parametrageFacture = $parametrageFactureRepository->findOneBy(['TypeDocument' => 'Facture']);
        if ($parametrageFacture) {
            $numFacture = $parametrageFacture->getPrefixe();
            if ($parametrageFacture->isAnneeEnCours()){
                $numFacture = $numFacture.date("Y");
            }

            $numFacture = $numFacture.$parametrageFacture->getNumeroAGenerer();

            if ($parametrageFacture->isCompletionAvecZero() && 
                strlen($numFacture) < $parametrageFacture->getNombreCaractereTotal()){
                $nbZeroAMettre = $parametrageFacture->getNombreCaractereTotal() - strlen($numFacture);
                
                $lesZeros = '';
                for ($i = 1; $i <= $nbZeroAMettre; $i++){
                    $lesZeros = $lesZeros.'0';
                }                
                $numFacture = insertToStr($numFacture, $lesZeros, (strlen($numFacture) - strlen($parametrageFacture->getNumeroAGenerer())));
            }

            $facture->setNumFacture($numFacture);
            $parametrageFacture->setNumeroAGenerer($parametrageFacture->getNumeroAGenerer() + 1);
            $parametrageFactureRepository->save($parametrageFacture, true);
        }

        $facture->setRemise($devis->getRemise());
        $facture->setPrixHT($devis->getPrixHT());
        $facture->setPrixTTC($devis->getPrixTTC());

        $facture->setPlusutilise(false);

        $facture->setDevis($devis);

        $modeReglementDefaut = $modeReglementRepository->findOneBy(["Libelle" => "Virement bancaire"]);
        $echeance = new Echeance();
        $echeance->setModeReglement($modeReglementDefaut);
        $echeance->setIsRegle(false);
        $echeance->setMontant($facture->getPrixTTC());

        $facture->addEcheance($echeance);

        $factureRepository->save($facture, true);

        $this->addFlash('success', 'Transformation du devis en facture réussie'); 
        return $this->redirectToRoute('app_facture_detail', ["id" => $facture->getId()]);
    }

}
