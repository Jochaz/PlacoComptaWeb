<?php

namespace App\Controller;

use App\Entity\Particulier;
use App\Form\CustomerType;
use App\Form\SearchParticulierType;
use App\Model\SearchDataParticulier;
use App\Repository\ParticulierRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class ClientController extends AbstractController
{
    #[Route('/customer', name: 'app_customer')]
    public function customer(ParticulierRepository $particulierRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $searchData = new SearchDataParticulier();
        $form = $this->createForm(SearchParticulierType::class, $searchData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $pagination = $paginator->paginate(
                $particulierRepository->findBySearch($searchData),
                $request->query->get('page', 1),
                10
            );
    
            return $this->render('client/customer/index.html.twig', [
                'pagination' => $pagination,
                'form' => $form
            ]);
        }

        $pagination = $paginator->paginate(
            $particulierRepository->paginationQuery(),
            $request->query->get('page', 1),
            10
        );

        return $this->render('client/customer/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form
        ]);
    }

    #[Route('/customer/detail/{id}', name: 'app_customer_detail')]
    public function customerDetail(string $id, Request $request, ParticulierRepository $particulierRepository, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $particulier = $particulierRepository->find($id);

        if (!$particulier || !$particulier->isActif()){
            return $this->redirectToRoute('app_customer');
        }
        $form = $this->createForm(CustomerType::class, $particulier);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isvalid()){
            $em->persist($particulier);
            $em->flush();

            $this->addFlash('success', 'Client modifié avec succès');
           // return $this->redirectToRoute('app_materiaux');
        }
        return $this->render('client/customer/detail.html.twig', [
            'particulier' => $particulier,
            'form' => $form->createView()
        ]);
    }

    #[Route('/customer/add', name: 'app_customer_add')]
    public function customerAdd(EntityManagerInterface $em, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $particulier = new Particulier();
        $form = $this->createForm(CustomerType::class, $particulier);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isvalid()){
            $particulier->setCreatedAt(new DateTimeImmutable());
            $particulier->setActif(true);

            $em->persist($particulier);
            $em->flush();

            $this->addFlash('success', 'Client ajouté avec succès');
           // return $this->redirectToRoute('app_materiaux');
        }

        return $this->render('client/customer/ajout.html.twig', [
            'form' => $form->createView()
        ]);

    }
    
    #[Route('/customer/disable/{id}', name: 'app_customer_disable')]
    public function customerDisable(string $id, ParticulierRepository $particulierRepository, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $particulier = $particulierRepository->find($id);
        if (!$particulier || !$particulier->isActif()){
           return $this->redirectToRoute('app_customer');
        }
        $particulier->setActif(false);
        $em->persist($particulier);
        $em->flush();

        $this->addFlash('success', 'Client modifié avec succès');
        return $this->redirectToRoute('app_customer');
    
        return $this->render('client/customer/detail.html.twig', [
            'particulier' => $particulier
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
