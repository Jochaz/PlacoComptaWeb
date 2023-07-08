<?php

namespace App\Controller;

use App\Entity\AdresseDocument;
use App\Entity\AdresseFacturation;
use App\Entity\Devis;
use App\Entity\LigneDevis;
use App\Entity\Materiaux;
use App\Form\AdresseChantierType;
use App\Form\AdresseFacturationType;
use App\Form\DevisInfoGeneraleType;
use App\Form\ModelePieceType;
use App\Form\SearchDevisType;
use App\Model\SearchDevisData;
use App\Repository\AdresseDocumentRepository;
use App\Repository\AdresseFacturationRepository;
use App\Repository\CategorieMateriauxRepository;
use App\Repository\DevisRepository;
use App\Repository\LigneDevisRepository;
use App\Repository\MateriauxRepository;
use App\Repository\ModelePieceRepository;
use App\Repository\ParametrageDevisRepository;
use App\Repository\TVARepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DevisController extends AbstractController
{
    #[Route('/quote', name: 'app_devis')]
    public function index(DevisRepository $devisRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
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
                'form' => $form
            ]);
        }

        $pagination = $paginator->paginate(
            $devisRepository->paginationQuery(),
            $request->query->get('page', 1),
            10
        );

        return $this->render('devis/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    #[Route('/quote/add/info', name: 'app_devis_add_info')]
    public function addInfo(Request $request, EntityManagerInterface $em, ParametrageDevisRepository $parametrageDevisRepository): Response
    {
        function insertToString(string $mainstr,string $insertstr,int $index):string
        {
            return substr($mainstr, 0, $index) . $insertstr . substr($mainstr, $index);
        }
        

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
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
            $em->persist($devis);
            $em->flush();

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
    AdresseFacturationRepository $adresseFacturationRepository,
    DevisRepository $devisRepository): Response
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
                $devisRepository->save($devis);      
                return $this->redirectToRoute('app_devis_add_ligne', ["devis" => serialize($devis)]);     
            } else {
                return $this->redirectToRoute('app_devis_add_adresse_facturation_devis', ["devis" => serialize($devis)]);
            }
        }

        return $this->render('devis/add/adresse_chantier.html.twig', [
            'form' => $form->createView(),
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
            $devisRepository->save($devis); 
            return $this->redirectToRoute('app_devis_add_ligne', ["devis" => serialize($devis)]);     
        }

        return $this->render('devis/add/adresse_chantier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/quote/add/ligne', name: 'app_devis_add_ligne')]
    public function addDevisLigne(Request $request, 
    ModelePieceRepository $modelePieceRepository, 
    TVARepository $tVARepository, 
    DevisRepository $devisRepository,
    MateriauxRepository $materiauxRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $devis = unserialize($request->query->get('devis'));
        if (!$devis) {
            $devis = $devisRepository->findBy(["id" => $request->request->get("devis")]);
            if ($devis) {
                $devis = $devis[0];
            }
        }

        if (!$devis){
            return $this->redirectToRoute('app_devis_add_info');
        }

        if ($request->getMethod() == Request::METHOD_POST){
            //On va parcourir toutes les données
            foreach ($request->request as $key => $value){
                if (str_contains($key, 'checked_materiaux_')) {
                    $params = explode('_', $key);
                    //0 et 1 - nom / 2 - id modele / 3 - id materiaux
                    $identifiant = "_".$params[2]."_".$params[3];
                    $materiaux = $materiauxRepository->find(["id" => $params[3]]);

                    $ligneDevis = new LigneDevis();
                    $ligneDevis->setDevis($devis);

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
            $devisRepository->save($devis);
            dump($devis);
        }


        $modelesPiece = $modelePieceRepository->findByUse();
        $tvas = $tVARepository->findAll();

        return $this->render('devis/add/lignes.html.twig', [
            'modelesPiece' =>$modelesPiece,
            'tvas' => $tvas,
            'devis' => $devis->getId()
        ]);
    }
}
