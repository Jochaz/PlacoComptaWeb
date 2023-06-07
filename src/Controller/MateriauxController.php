<?php

namespace App\Controller;

use App\Repository\MateriauxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MateriauxController extends AbstractController
{
    #[Route('/material', name: 'app_materiaux')]
    public function index(MateriauxRepository $materiauxRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $materiauxRepository->paginationQuery(),
            $request->query->get('page', 1),
            10
        );

        return $this->render('materiaux/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
