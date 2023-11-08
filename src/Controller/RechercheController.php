<?php

namespace App\Controller;

use App\Form\SearchProfessionnelType;
use App\Model\SearchData;
use App\Model\SearchDevisData;
use App\Model\SearchFactureData;
use App\Model\SearchParticulierData;
use App\Repository\DevisRepository;
use App\Repository\FactureRepository;
use App\Repository\MateriauxRepository;
use App\Repository\ParticulierRepository;
use App\Repository\ProfessionnelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(Request $request, MateriauxRepository $materiauxRepository, ParticulierRepository $particulierRepository,
    ProfessionnelRepository $professionnelRepository, DevisRepository $devisRepository, FactureRepository $factureRepository): Response
    {
        $recherche = $request->query->get('search_recherche');

        $searchData = new SearchData();
        $searchData->libelle = $recherche;
        $materiaux = $materiauxRepository->findBySearch($searchData);

        //Nom client particulier
        $particuliers = $particulierRepository->findByRecherche($recherche);
        //Nom societe
        $professionnels = $professionnelRepository->findByRecherche($recherche);

        $searchDataDevis = new SearchDevisData();
        $searchDataDevis->numDevis = $recherche;
        $devis = $devisRepository->findByRecherche($searchDataDevis);

        $searchDataFacture = new SearchFactureData();
        $searchDataFacture->NumFacture = $recherche;
        $facture = $factureRepository->findByRecherche($searchDataFacture);

        $totalFound = count($materiaux) + count($particuliers) + count($professionnels) + count($devis) + count($facture);    
        return $this->render('recherche/index.html.twig', [
            'Materiaux' => $materiaux,
            'Particuliers' => $particuliers,
            'Professionnels' => $professionnels,
            'Devis' => $devis,
            'Facture' => $facture,
            'totalFound' => $totalFound
        ]);
    }
}
