<?php

namespace App\Controller;

use App\Form\SearchFactureType;
use App\Model\SearchFactureData;
use App\Repository\FactureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
