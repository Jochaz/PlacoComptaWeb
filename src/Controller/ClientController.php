<?php

namespace App\Controller;

use App\Entity\Particulier;
use App\Entity\Professionnel;
use App\Form\CustomerType;
use App\Form\ProfessionalType;
use App\Form\SearchParticulierType;
use App\Form\SearchProfessionnelType;
use App\Model\SearchDataParticulier;
use App\Model\SearchDataProfessionnel;
use App\Repository\ParticulierRepository;
use App\Repository\ProfessionnelRepository;
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
    public function professional(Request $request, PaginatorInterface $paginator, ProfessionnelRepository $professionnelRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $searchData = new SearchDataProfessionnel();
        $form = $this->createForm(SearchProfessionnelType::class, $searchData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $pagination = $paginator->paginate(
                $professionnelRepository->findBySearch($searchData),
                $request->query->get('page', 1),
                10
            );
    
            return $this->render('client/professional/index.html.twig', [
                'pagination' => $pagination,
                'form' => $form
            ]);
        }

        $pagination = $paginator->paginate(
            $professionnelRepository->paginationQuery(),
            $request->query->get('page', 1),
            10
        );

        return $this->render('client/professional/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form
        ]);
    }

    #[Route('/professional/detail/{id}', name: 'app_professional_detail')]
    public function professionalDetail(string $id, ProfessionnelRepository $professionnelRepository, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $professionnel = $professionnelRepository->find($id);

        if (!$professionnel || !$professionnel->isActif()){
            return $this->redirectToRoute('app_customer');
        }
        $form = $this->createForm(ProfessionalType::class, $professionnel);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isvalid()){
            $em->persist($professionnel);
            $em->flush();

            $this->addFlash('success', 'Client modifié avec succès');
           // return $this->redirectToRoute('app_materiaux');
        }
        return $this->render('client/professional/detail.html.twig', [
            'professionnel' => $professionnel,
            'form' => $form->createView()
        ]);
    }

    #[Route('/professional/add', name: 'app_professional_add')]
    public function professionalAdd(EntityManagerInterface $em, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $professionnel = new Professionnel();
        $form = $this->createForm(ProfessionalType::class, $professionnel);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isvalid()){
            $professionnel->setCreatedAt(new DateTimeImmutable());
            $professionnel->setActif(true);

            $em->persist($professionnel);
            $em->flush();

            $this->addFlash('success', 'Client ajouté avec succès');
           // return $this->redirectToRoute('app_materiaux');
        }

        return $this->render('client/professional/ajout.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/professional/disable/{id}', name: 'app_professional_disable')]
    public function professionalDisable(string $id, ProfessionnelRepository $professionnelRepository, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $professionnel = $professionnelRepository->find($id);
        if (!$professionnel || !$professionnel->isActif()){
           return $this->redirectToRoute('app_professional');
        }
        $professionnel->setActif(false);
        $em->persist($professionnel);
        $em->flush();

        $this->addFlash('success', 'Client modifié avec succès');
        return $this->redirectToRoute('app_professional');
    
        return $this->render('client/professional/detail.html.twig', [
            'particulier' => $professionnel
        ]);
    }
}
