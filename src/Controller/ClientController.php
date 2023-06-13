<?php

namespace App\Controller;

use App\Repository\ParticulierRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/customer', name: 'app_customer')]
    public function customer(ParticulierRepository $particulierRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $pagination = $paginator->paginate(
            $particulierRepository->paginationQuery(),
            $request->query->get('page', 1),
            10
        );

        return $this->render('client/customer/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/customer/detail/{id}', name: 'app_customer_detail')]
    public function customerDetail(string $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/customer/add', name: 'app_customer_add')]
    public function customerAdd(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
    
    #[Route('/customer/disable/{id}', name: 'app_customer_disable')]
    public function customerDisable(string $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////PARTIE PRO////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

    #[Route('/professional', name: 'app_professional')]
    public function professional(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/professional/detail/{id}', name: 'app_professional')]
    public function professionalDetail(string $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/professional/add', name: 'app_professional')]
    public function professionalAdd(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/professional/disable/{id}', name: 'app_professional')]
    public function professionalDisable(string $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
}
