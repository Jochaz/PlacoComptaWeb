<?php

namespace App\Controller;

use App\Entity\Materiaux;
use App\Form\MateriauxType;
use App\Repository\MateriauxRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $pagination = $paginator->paginate(
            $materiauxRepository->paginationQuery(),
            $request->query->get('page', 1),
            10
        );

        return $this->render('materiaux/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/material/detail/{id}', name: 'app_materiaux_id')]
    public function detail(string $id, MateriauxRepository $materiauxRepository, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $materiaux = $materiauxRepository->find($id);

        if (!$materiaux || $materiaux->isPlusUtilise()){
            return $this->redirectToRoute('app_materiaux');
        }
        $form = $this->createForm(MateriauxType::class, $materiaux);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isvalid()){
            $em->persist($materiaux);
            $em->flush();

            $this->addFlash('success', 'Matériaux modifié avec succès');
           // return $this->redirectToRoute('app_materiaux');
        }
        return $this->render('materiaux/detail.html.twig', [
            'materiaux' => $materiaux,
            'form' => $form->createView()
        ]);
    }

    #[Route('/material/add', name: 'app_materiaux_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $materiaux = new Materiaux();
        $form = $this->createForm(MateriauxType::class, $materiaux);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isvalid()){
            $em->persist($materiaux);
            $em->flush();

            $this->addFlash('success', 'Matériaux ajouté avec succès');
           // return $this->redirectToRoute('app_materiaux');
        }

        return $this->render('materiaux/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/material/delete/{id}', name: 'app_materiaux_add')]
    public function delete(string $id, MateriauxRepository $materiauxRepository, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $materiaux = $materiauxRepository->find($id);
        if (!$materiaux || $materiaux->isPlusUtilise()){
           return $this->redirectToRoute('app_materiaux');
        }
        $materiaux->setPlusUtilise(true);
        $em->persist($materiaux);
        $em->flush();

        $this->addFlash('success', 'Matériaux modifié avec succès');
        return $this->redirectToRoute('app_materiaux');
    
        return $this->render('materiaux/detail.html.twig', [
            'materiaux' => $materiaux
        ]);
    }
}
