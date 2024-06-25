<?php

namespace App\Controller;

use App\Entity\CategorieMateriaux;
use App\Entity\EnteteDocument;
use App\Entity\Materiaux;
use App\Entity\ModelePiece;
use App\Entity\ParametrageDevis;
use App\Entity\ParametrageFacture;
use App\Entity\TVA;
use App\Entity\UniteMesure;
use App\Form\CategorieMateriauxType;
use App\Form\EnteteDocumentType;
use App\Form\ModelePieceType;
use App\Form\ParametrageDevisType;
use App\Form\ParametrageFactureType;
use App\Form\TVAType;
use App\Form\UniteMesureType;
use App\Repository\CategorieMateriauxRepository;
use App\Repository\EnteteDocumentRepository;
use App\Repository\ModelePieceRepository;
use App\Repository\ParametrageDevisRepository;
use App\Repository\ParametrageFactureRepository;
use App\Repository\TVARepository;
use App\Repository\UniteMesureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfigurationController extends AbstractController
{
    #[Route('/configuration', name: 'app_configuration')]
    public function index(
        UniteMesureRepository $uniteMesureRepository,
        TVARepository $tVARepository,
        CategorieMateriauxRepository $categorieMateriauxRepository,
        ParametrageDevisRepository $ParametrageDevisRepository,
        ParametrageFactureRepository $ParametrageFactureRepository,
        EnteteDocumentRepository $enteteDocumentRepository,
        ModelePieceRepository $modelePieceRepository,
        Request $request
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $TVA = $tVARepository->findByValue();

        $UM = $uniteMesureRepository->findByUse();

        $Categories = $categorieMateriauxRepository->findByUse();

        $modelesPiece = $modelePieceRepository->findByUse();

        $Devis = $ParametrageDevisRepository->findOneBy(['TypeDocument' => 'Devis']);
        if (!$Devis) {
            $Devis = new ParametrageDevis();
        }
        $formDevis = $this->createForm(ParametrageDevisType::class, $Devis);
        $formDevis->handleRequest($request);
        if ($formDevis->isSubmitted() && $formDevis->isvalid()) {
            $ParametrageDevisRepository->save($Devis, true);
            return $this->redirectToRoute('app_configuration');
        }

        $Facture = $ParametrageFactureRepository->findOneBy(['TypeDocument' => 'Facture']);
        if (!$Facture) {
            $Facture = new ParametrageFacture();
        }
        $formFacture = $this->createForm(ParametrageFactureType::class, $Facture);
        $formFacture->handleRequest($request);
        if ($formFacture->isSubmitted() && $formFacture->isvalid()) {
            $ParametrageFactureRepository->save($Facture, true);
            return $this->redirectToRoute('app_configuration');
        }

        $EntetesDocument = $enteteDocumentRepository->findAll();

        if (count($EntetesDocument) == 0) {
            $EnteteDocument = new EnteteDocument();
        } else {
            $EnteteDocument = $EntetesDocument[0];
        }

        $formEnteteDocument = $this->createForm(EnteteDocumentType::class, $EnteteDocument);
        $formEnteteDocument->handleRequest($request);
        if ($formEnteteDocument->isSubmitted() && $formEnteteDocument->isvalid()) {
            $enteteDocumentRepository->save($EnteteDocument, true);
            return $this->redirectToRoute('app_configuration');
        }


        return $this->render('configuration/index.html.twig', [
            'TVA' => $TVA,
            'UM' => $UM,
            'Categories' => $Categories,
            'ModelesPiece' => $modelesPiece,
            'formDevis' => $formDevis->createView(),
            'formFacture' => $formFacture->createView(),
            'formEnteteDocument' => $formEnteteDocument->createView()
        ]);
    }

    ///////////////////////////////////////AJOUT/////////////////////////////////////////////////////////////

    #[Route('/configuration/TVA/add', name: 'app_configuration_TVA_add')]
    public function TVAAdd(Request $request, TVARepository $tVARepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $tva = new TVA();
        $form = $this->createForm(TVAType::class, $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {

            $tVARepository->save($tva, true);

            return $this->redirectToRoute('app_configuration');
            // return $this->redirectToRoute('app_materiaux');
        }


        return $this->render('configuration/TVA/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/configuration/UM/add', name: 'app_configuration_UM_add')]
    public function UMAdd(Request $request, UniteMesureRepository $uniteMesureRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $UniteMesure = new UniteMesure();
        $form = $this->createForm(UniteMesureType::class, $UniteMesure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $UniteMesure->setPlusUtilise(false);
            $uniteMesureRepository->save($UniteMesure, true);

            return $this->redirectToRoute('app_configuration');
            // return $this->redirectToRoute('app_materiaux');
        }


        return $this->render('configuration/UM/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/configuration/categorie/add', name: 'app_configuration_categorie_add')]
    public function categorieAdd(Request $request, CategorieMateriauxRepository $categorieMateriauxRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $categorie = new CategorieMateriaux();
        $form = $this->createForm(CategorieMateriauxType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $categorie->setPlusUtilise(false);
            $categorieMateriauxRepository->save($categorie, true);

            return $this->redirectToRoute('app_configuration');
            // return $this->redirectToRoute('app_materiaux');
        }


        return $this->render('configuration/categories/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/configuration/modelepiece/add', name: 'app_configuration_modele_piece_add')]
    public function modelePieceAdd(Request $request, ModelePieceRepository $modelePieceRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $modelePiece = new ModelePiece();
        $form = $this->createForm(ModelePieceType::class, $modelePiece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $modelePiece->setPlusUtilise(false);
            $modelePieceRepository->save($modelePiece, true);

            return $this->redirectToRoute('app_configuration');
            // return $this->redirectToRoute('app_materiaux');
        }


        return $this->render('configuration/modelepiece/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
    //////////////////////////////////////////////////////////DETAIL///////////////////////////////////////////////////////////
    #[Route('/configuration/TVA/{id}', name: 'app_TVA_detail')]
    public function TVADetail(string $id, Request $request, TVARepository $tVARepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $tva = $tVARepository->find($id);

        if (!$tva) {
            return $this->redirectToRoute('app_configuration');
        }

        $form = $this->createForm(TVAType::class, $tva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $tVARepository->save($tva, true);

            $this->addFlash('success', 'TVA modifié avec succès');
            // return $this->redirectToRoute('app_materiaux');
        }
        return $this->render('configuration/TVA/detail.html.twig', [
            'tva' => $tva,
            'form' => $form->createView()
        ]);
    }

    #[Route('/configuration/UM/{id}', name: 'app_UM_detail')]
    public function UMDetail(string $id, Request $request, UniteMesureRepository $uniteMesureRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $um = $uniteMesureRepository->find($id);

        if (!$um || $um->isPlusUtilise()) {
            return $this->redirectToRoute('app_configuration');
        }
        $form = $this->createForm(UniteMesureType::class, $um);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $uniteMesureRepository->save($um, true);

            $this->addFlash('success', 'Unité de mesure modifié avec succès');
            // return $this->redirectToRoute('app_materiaux');
        }
        return $this->render('configuration/UM/detail.html.twig', [
            'um' => $um,
            'form' => $form->createView()
        ]);
    }

    #[Route('/configuration/categorie/{id}', name: 'app_categorie_detail')]
    public function categorieDetail(string $id, Request $request, CategorieMateriauxRepository $categorieMateriauxRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $categorie = $categorieMateriauxRepository->find($id);
        if (!$categorie || $categorie->isPlusUtilise()) {
            return $this->redirectToRoute('app_configuration');
        }
        $form = $this->createForm(CategorieMateriauxType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $categorieMateriauxRepository->save($categorie, true);

            $this->addFlash('success', 'Catégorie de matériaux modifié avec succès');
            // return $this->redirectToRoute('app_materiaux');
        }
        return $this->render('configuration/categories/detail.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView()
        ]);
    }

    #[Route('/configuration/modelepiece/{id}', name: 'app_modele_piece_detail')]
    public function modelePieceDetail(string $id, Request $request, modelePieceRepository $modelePieceRepository, TVARepository $tVARepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $modelePiece = $modelePieceRepository->find($id);
        if (!$modelePiece || $modelePiece->isPlusUtilise()) {
            return $this->redirectToRoute('app_configuration');
        }

        $form = $this->createForm(ModelePieceType::class, $modelePiece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $modelePieceRepository->save($modelePiece, true);

            $this->addFlash('success', 'Modèle de pièce modifié avec succès');
            // return $this->redirectToRoute('app_materiaux');
        }
        return $this->render('configuration/modelepiece/detail.html.twig', [
            'modelepiece' => $modelePiece,
            'form' => $form->createView()
        ]);
    }


    /////////////DISABLE//////////////////
    // #[Route('/configuration/TVA/delete/{id}', name: 'app_disable_TVA')]
    // public function disableTVA(string $id, TVARepository $tVARepository, Request $request, EntityManagerInterface $em): Response
    // {
    //     if (!$this->getUser()) {
    //         return $this->redirectToRoute('app_login');
    //     }
    //     $tva = $tVARepository->find($id);
    //     if (!$tva || $tva->isPlusUtilise()){
    //        return $this->redirectToRoute('app_materiaux');
    //     }
    //     $materiaux->setPlusUtilise(true);
    //     $em->persist($materiaux);
    //     $em->flush();

    //     $this->addFlash('success', 'Matériaux modifié avec succès');
    //     return $this->redirectToRoute('app_materiaux');

    //     return $this->render('materiaux/detail.html.twig', [
    //         'materiaux' => $materiaux
    //     ]);
    // }

    #[Route('/configuration/UM/disable/{id}', name: 'app_UM_disable')]
    public function disableUM(string $id, UniteMesureRepository $uniteMesureRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $um = $uniteMesureRepository->find($id);
        if (!$um || $um->isPlusUtilise()) {
            return $this->redirectToRoute('app_configuration');
        }
        $um->setPlusUtilise(true);

        $uniteMesureRepository->save($um, true);

        $this->addFlash('success', 'Unité de mesure désactivé avec succès');
        return $this->redirectToRoute('app_configuration');

        return $this->render('configuration/UM/detail.html.twig', [
            'um' => $um
        ]);
    }

    #[Route('/configuration/categorie/disable/{id}', name: 'app_materiaux_delete')]
    public function delete(string $id, CategorieMateriauxRepository $CategorieMateriauxRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $categorie = $CategorieMateriauxRepository->find($id);
        if (!$categorie || $categorie->isPlusUtilise()) {
            return $this->redirectToRoute('app_configuration');
        }
        $categorie->setPlusUtilise(true);
        $CategorieMateriauxRepository->save($categorie, true);

        $this->addFlash('success', 'Modèle de pièce modifié avec succès');
        return $this->redirectToRoute('app_configuration');

        return $this->render('configuration/categories/detail.html.twig', [
            'categorie' => $categorie
        ]);
    }

    #[Route('/configuration/modelepiece/disable/{id}', name: 'app_modele_piece_delete')]
    public function deleteModelePiece(string $id, ModelePieceRepository $modelePieceRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $modelePiece = $modelePieceRepository->find($id);
        if (!$modelePiece || $modelePiece->isPlusUtilise()) {
            return $this->redirectToRoute('app_configuration');
        }
        $modelePiece->setPlusUtilise(true);
        $modelePieceRepository->save($modelePiece, true);

        $this->addFlash('success', 'Modèle de pièce modifié avec succès');
        return $this->redirectToRoute('app_configuration');

        return $this->render('configuration/modelepiece/detail.html.twig', [
            'modelepiece' => $modelePiece
        ]);
    }
}
