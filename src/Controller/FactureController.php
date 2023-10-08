<?php

namespace App\Controller;

use App\Entity\AdresseDocument;
use App\Entity\AdresseFacturation;
use App\Entity\Facture;
use App\Entity\LigneFacture;
use App\Form\AdresseChantierType;
use App\Form\FactureDetailType;
use App\Form\FactureInfoGeneraleType;
use App\Form\FactureRemiseType;
use App\Form\ParametrageFactureType;
use App\Form\SearchFactureType;
use App\Model\SearchFactureData;
use App\Repository\AdresseDocumentRepository;
use App\Repository\AdresseFacturationRepository;
use App\Repository\EnteteDocumentRepository;
use App\Repository\FactureRepository;
use App\Repository\LigneFactureRepository;
use App\Repository\MateriauxRepository;
use App\Repository\ModelePieceRepository;
use App\Repository\ParametrageFactureRepository;
use App\Repository\TVARepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\isNull;

class FactureController extends AbstractController
{
    #[Route('/invoice', name: 'app_facture')]
    public function index(FactureRepository $factureRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $searchData = new SearchFactureData();
        $form = $this->createForm(SearchFactureType::class, $searchData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $pagination = $paginator->paginate(
                $factureRepository->findBySearch($searchData),
                $request->query->get('page', 1),
                10
            );
    
            return $this->render('facture/index.html.twig', [
                'pagination' => $pagination,
                'form' => $form
            ]);
        }

        $pagination = $paginator->paginate(
            $factureRepository->paginationQuery(),
            $request->query->get('page', 1),
            10
        );

        return $this->render('facture/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    #[Route('/invoice/add/info', name: 'app_facture_add_info')]
    public function addInfo(Request $request, FactureRepository $factureRepository, 
                            ParametrageFactureRepository $parametrageFactureRepository): Response
    {
        function insertToString(string $mainstr,string $insertstr,int $index):string
        {
            return substr($mainstr, 0, $index) . $insertstr . substr($mainstr, $index);
        }
        

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $facture = new Facture();
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
                $numFacture = insertToString($numFacture, $lesZeros, (strlen($numFacture) - strlen($parametrageFacture->getNumeroAGenerer())));
            }

            $facture->setNumFacture($numFacture);
            $facture->setDateFacture(new \DateTime());
        }

        $form = $this->createForm(FactureInfoGeneraleType::class, $facture);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            //Si pro
            if($request->request->get("SwitchTypeClient")){
                $facture->setParticulier(null);
            } else {
                $facture->setProfessionnel(null);
            }
            
            $facture->setPlusutilise(false);
            $factureRepository->save($facture, true);

            $parametrageFacture->setNumeroAGenerer($parametrageFacture->getNumeroAGenerer() + 1);
            $parametrageFactureRepository->save($parametrageFacture, true);

            return $this->redirectToRoute('app_facture_add_adresse_facture', ["facture" => serialize($facture)]);
        }

        return $this->render('facture/add/info.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/invoice/add/adressefacture', name: 'app_facture_add_adresse_facture')]
    public function addAdresseChantier(Request $request, AdresseDocumentRepository $adresseChantierRepository, 
    AdresseFacturationRepository $adresseFacturationRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$request->query->get('facture')){
            return $this->redirectToRoute('app_facture_add_info');
        }
        $facture = unserialize($request->query->get('facture'));
        $adresseFacture = new AdresseDocument();

        $form= $this->createForm(AdresseChantierType::class, $adresseFacture);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $facture->setAdresseChantier($adresseFacture);
            $adresseChantierRepository->save($adresseFacture, true);
 
            if ($request->request->get('copieAdresseChantierSurFacturation')) {
                $adresseFacturation = new AdresseFacturation();
                $adresseFacturation->setLigne1($adresseFacture->getLigne1());
                $adresseFacturation->setLigne2($adresseFacture->getLigne2());
                $adresseFacturation->setLigne3($adresseFacture->getLigne3());
                $adresseFacturation->setVille($adresseFacture->getVille());
                $adresseFacturation->setCP($adresseFacture->getCP());
                $adresseFacturation->setBoitePostale($adresseFacture->getBoitePostale());

                $facture->setAdresseFacturation($adresseFacturation);  
                $adresseFacturationRepository->save($adresseFacturation, true);  
                return $this->redirectToRoute('app_facture_add_ligne', ["facture" => serialize($facture)]);     
            } else {
                return $this->redirectToRoute('app_facture_add_adresse_facturation_facture', ["facture" => serialize($facture)]);
            }
            
        }

        return $this->render('facture/add/adresse_chantier.html.twig', [
            'form' => $form->createView(),
            'modif' => false,
        ]);
    }

    #[Route('/invoice/add/adressefacturation', name: 'app_facture_add_adresse_facturation_facture')]
    public function addAdresseFacturation(Request $request, AdresseFacturationRepository $adresseFacturationRepository, FactureRepository $factureRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$request->query->get('facture')){
            return $this->redirectToRoute('app_facture_add_info');
        }
        $facture = unserialize($request->query->get('facture'));
        $adresse = new AdresseFacturation();

        $form= $this->createForm(AdresseFacturationType::class, $adresse);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $facture->setAdresseFacturation($adresse);
            $adresseFacturationRepository->save($adresse, true); 
            $factureRepository->save($facture, true); 
            return $this->redirectToRoute('app_facture_add_ligne', ["facture" => serialize($facture)]);     
        }

        return $this->render('facture/add/adresse_chantier.html.twig', [
            'form' => $form->createView(),
            'modif' => false,
        ]);
    }

    #[Route('/invoice/add/ligne', name: 'app_facture_add_ligne')]
    public function addFactureLigne(Request $request, 
    ModelePieceRepository $modelePieceRepository, 
    TVARepository $tVARepository, 
    FactureRepository $factureRepository,

    MateriauxRepository $materiauxRepository,
    AdresseDocumentRepository $adresseChantierRepository,
    AdresseFacturationRepository $adresseFacturationRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $facture = unserialize($request->query->get('facture'));
        if (!$facture && $request->getMethod() != Request::METHOD_POST){
            return $this->redirectToRoute('app_facture_add_info');
        }

        if ($request->getMethod() == Request::METHOD_POST){
            $facture = $factureRepository->findWithJoin(intval($request->request->get("facture")));
            if ($request->request->get('adresseC')){
           
                $AdresseChantier = $adresseChantierRepository->findOneBy(["id" => $request->request->get('adresseC')]);
                $facture->setAdresseChantier($AdresseChantier);
            }

            if ($request->request->get('adresseF')){
             
                $AdresseFacturation = $adresseFacturationRepository->findOneBy(["id" => $request->request->get('adresseF')]);
                $facture->setAdresseFacturation($AdresseFacturation);
            }

            //On va parcourir toutes les données
            foreach ($request->request as $key => $value){
             
                if (str_contains($key, 'checked_materiaux_')) {
                    $params = explode('_', $key);
                    //0 et 1 - nom / 2 - id modele / 3 - id materiaux
                    $identifiant = "_".$params[2]."_".$params[3];
                    $materiaux = $materiauxRepository->find(["id" => $params[3]]);

                    $ligneFacture = new LigneFacture();
                  

                    if ($request->request->get('des'.$identifiant)){
                        $ligneFacture->setDesignation($request->request->get('des'.$identifiant));
                    } else {
                        $ligneFacture->setDesignation($materiaux->getDesignation());
                    }
                    
                    if ($request->request->get('pu'.$identifiant)){
                        $ligneFacture->setPrixUnitaire(floatval($request->request->get('pu'.$identifiant)));
                    } else {                   
                        $ligneFacture->setPrixUnitaire($materiaux->getPrixUnitaire());
                    }

                    if ($request->request->get('qte'.$identifiant)){
                        $ligneFacture->setQte($request->request->get('qte'.$identifiant));
                    } else {
                        $ligneFacture->setQte(1);
                    }

                    if ($request->request->get('remise'.$identifiant)){
                        $ligneFacture->setRemise(floatval($request->request->get('remise'.$identifiant)));
                    } else {
                        $ligneFacture->setRemise(0);
                    }

                    if ($request->request->get('tva'.$identifiant)){
                        $ligneFacture->setTVA($tVARepository->findOneBy(["id" => $request->request->get('tva'.$identifiant)]));
                    }
                    $ligneFacture->setMateriaux($materiaux);
                    $facture->addLigneFacture($ligneFacture);
                }
            }
            $factureRepository->save($facture, true);
            return $this->redirectToRoute('app_facture_add_recap', ["facture" => serialize($facture)]);
        } 
        $modelesPiece = $modelePieceRepository->findByUse();
        $tvas = $tVARepository->findAll();

        return $this->render('facture/add/lignes.html.twig', [
            'modelesPiece' =>$modelesPiece,
            'tvas' => $tvas,
            'facture' => $facture
        ]);

    }

    #[Route('/invoice/add/recap', name: 'app_facture_add_recap')]
    public function addRecapFacture(Request $request, FactureRepository $factureRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        if (!$request->query->get('facture')){
            return $this->redirectToRoute('app_facture_add_info');
        }
      
        $facture = unserialize($request->query->get('facture'));
        $facture = $factureRepository->findOneBy(["id" => $facture->getId()]);
        $form= $this->createForm(FactureRemiseType::class, $facture);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $facture->setPrixHT($facture->getPrixHT());
            $facture->setPrixTTC($facture->getPrixTTC());
            $factureRepository->save($facture, true);
            return $this->redirectToRoute('app_facture');     
        }
        
        dump($facture);
        return $this->render('facture/add/recap.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/invoice/detail/{id}', name: 'app_facture_detail')]
    public function quoteDetail(string $id, Request $request, FactureRepository $factureRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $facture = $factureRepository->findOneBy(["id" => $id]);

        if (!$facture){
            return $this->redirectToRoute('app_facture');
        }                 
        
        if (!$facture->getRemise()){
            $facture->setRemise(0);
        }
       
        $form = $this->createForm(FactureDetailType::class, $facture);
        $form->handleRequest($request);        
        if($form->isSubmitted() && $form->isvalid()){
            $facture->setPrixHT($facture->getPrixHT());
            $facture->setPrixTTC($facture->getPrixTTC());
            $factureRepository->save($facture, true);
            return $this->redirectToRoute('app_facture_detail', ["id" => $facture->getId()]);     
        }

        return $this->render('facture/detail.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/invoice/updatecontent/{id}', name: 'app_facture_contenu')]
    public function quotedContent(string $id, Request $request, FactureRepository $factureRepository, LigneFactureRepository $ligneFactureRepository, MateriauxRepository $materiauxRepository, TVARepository $tVARepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $facture = $factureRepository->findOneBy(["id" => $id]);
        $tvas = $tVARepository->findAll();
        $ligneAdd = $materiauxRepository->findByMateriauxManquantFacture($facture->getId());

        if (!$facture){
            return $this->redirectToRoute('app_facture');
        }

      
        if ($request->getMethod() == Request::METHOD_POST){
            //Si on est sur un ajout de materiaux    
            if ($request->request->get('materiaux_id'))
            {
                $materiaux = $materiauxRepository->find($request->request->get('materiaux_id'));
                $tva = $tVARepository->find($request->request->get('tva_add'));

                $ligneFacture = new LigneFacture();
                $ligneFacture->setMateriaux($materiaux);
                $ligneFacture->setTVA($tva);
                $ligneFacture->setDesignation($request->request->get('des_add'));
                $ligneFacture->setQte($request->request->get('qte_add'));
                if ($request->request->get('remise_add')) {
                    $ligneFacture->setRemise($request->request->get('remise_add'));
                } else {
                    $ligneFacture->setRemise(0);
                }                   
                $ligneFacture->setPrixUnitaire($request->request->get('pu_add'));
                $facture->addLigneFacture($ligneFacture);
            } else {
                foreach ($request->request as $key => $value){
                    if (str_contains($key, 'ligne_')){
                        $identifiant = $value;
                        foreach($facture->getLigneFacture() as $ligne){
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
                                
                                $ligneFactureRepository->save($ligne, true);
                            }
                        }
                    }
                }
            }
            $facture->setPrixHT($facture->getPrixHT());
            $facture->setPrixTTC($facture->getPrixTTC());
            $factureRepository->save($facture, true);

            $this->addFlash('success', 'Facture modifiée avec succès');    
            return $this->redirectToRoute('app_facture_contenu', ["id" => $facture->getId()]);     
        }

        return $this->render('facture/contenu.html.twig', [
            'facture' => $facture, 
            'tvas' => $tvas,
            'ligneAdd' => $ligneAdd
        ]);
    }

    #[Route('/invoice/disable/{id}', name: 'app_facture_disable')]
    public function invoiceisable(string $id, FactureRepository $factureRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $facture = $factureRepository->findOneBy(["id" => $id]);

        if (!$facture){
            return $this->redirectToRoute('app_facture');
        }
        
        $facture->setPlusutilise(true);
        $factureRepository->save($facture, true);

        $this->addFlash('success', 'Facture désactivée avec succès');    
        return $this->redirectToRoute('app_facture');   
    }

    #[Route('/invoice/delete/ligne/{id}', name: 'app_facture_disable_line')]
    public function invoiceDisableLine(string $id, LigneFactureRepository $LigneFactureRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $ligne = $LigneFactureRepository->findOneBy(["id" => $id]);

        if (!$ligne){
            return $this->redirectToRoute('app_facture');
        }
        
        $LigneFactureRepository->remove($ligne, true);
        
        $this->addFlash('success', 'Ligne de facture supprimé avec succès');    
        return $this->redirectToRoute('app_facture_contenu', ["id" => $ligne->getFacture()->getId()]);      
    }

    #[Route('/invoice/generatePDF/{id}', name: 'app_facture_PDF')]
    public function invoiceGeneratePdf(string $id, FactureRepository $factureRepository, EnteteDocumentRepository $enteteDocumentRepository, LigneFactureRepository $ligneFactureRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $facture = $factureRepository->findOneBy(["id" => $id]);

        if (!$facture){
            return $this->redirectToRoute('app_facture');
        }

        $entete = $enteteDocumentRepository->findAll()[0];
        $LigneAdresseChantier = $facture->getAdresseChantier()->getLigne1();
        if (!isNull($facture->getAdresseChantier()->getLigne2())){
            $LigneAdresseChantier = $LigneAdresseChantier.'\n'.$facture->getAdresseChantier()->getLigne2();
        }
        if (!isNull($facture->getAdresseChantier()->getLigne3())){
            $LigneAdresseChantier = $LigneAdresseChantier.'\n'.$facture->getAdresseChantier()->getLigne3();
        }
        $LigneAdresseFacturation = $facture->getAdresseFacturation()->getLigne1();
        if (!isNull($facture->getAdresseFacturation()->getLigne2())){
            $LigneAdresseFacturation = $LigneAdresseFacturation.'\n'.$facture->getAdresseFacturation()->getLigne2();
        }
        if (!isNull($facture->getAdresseFacturation()->getLigne3())){
            $LigneAdresseFacturation = $LigneAdresseFacturation.'\n'.$facture->getAdresseFacturation()->getLigne3();
        }
        if($facture->getParticulier()){
            $nomprenom = $facture->getParticulier()->getNom()." ".$facture->getParticulier()->getPrenom();
        } else if ($facture->getProfessionnel()){
            $nomprenom = $facture->getProfessionnel()->getNomsociete();
        }

        $lignes = $ligneFactureRepository->findByIdFactureAndOrderByCategorie($facture->getId());

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

          // Retrieve the HTML generated in our twig file
          $html = $this->renderView('pdf/facture.html.twig', [
            'title' => "Facture N°".$facture->getNumFacture(),

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

            'NumFacture' => $facture->getNumFacture(),

            'Ville' => $entete->getVilleFaitA(),
            'DateFacture' => $facture->getDateFacture()->format('d-m-Y'),

            'NomPrenom' => $nomprenom,
            'LigneAdresseClient' => $LigneAdresseFacturation,
            
            'VilleCP' => $facture->getAdresseFacturation()->getCP().' '.$facture->getAdresseFacturation()->getVille(),

            'LigneAdresseChantier' => $LigneAdresseChantier,
            'CPVilleAdresseChantier' => $facture->getAdresseChantier()->getCP().' '.$facture->getAdresseChantier()->getVille(),

            'MontantHT' => $facture->getPrixHT(),
            'MontantTTC' => $facture->getPrixTTC(),
            'TotalTVA' => $facture->getPrixTTC() - $facture->getPrixHT(),

            'lignes' => $lignes,
            'facture' => $facture
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        ob_end_clean();
        $dompdf->stream($facture->getNumFacture().".pdf", [
            "Attachment" => 0
        ]);
        return new Response("The PDF file has been succesfully generated !");
    }
}
