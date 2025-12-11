<?php

namespace App\Controller;

use App\Entity\Docteur;
use App\Form\DocteurForm;
use App\Repository\DocteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DocteurController extends AbstractController
{
    #[Route('/docteur', name: 'app_docteur')]
    public function index(): Response
    {
        return $this->render('docteur/index.html.twig', [
            'controller_name' => 'DocteurController',
        ]);
    }

    #[Route('/docteurs', name: 'list_docteurs')]
    public function list(DocteurRepository $repo): Response
    {
        $docteurs = $repo->findAll();

        return $this->render('docteur/listDocteur.html.twig', [
            'docteurs' => $docteurs,
        ]);
    }

    #[Route('/docteur/add', name: 'add_docteur')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $docteur = new Docteur();
        $form = $this->createForm(DocteurForm::class, $docteur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($docteur);
            $em->flush();

            return $this->redirectToRoute('list_docteurs');
        }

        return $this->render('docteur/addDocteur.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/docteur/{id}/edit', name: 'docteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Docteur $docteur, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DocteurForm::class, $docteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('list_docteurs');
        }

        return $this->render('docteur/editDocteur.html.twig', [
            'docteur' => $docteur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/docteur/{id}', name: 'docteur_delete', methods: ['POST'])]
    public function delete(Request $request, Docteur $docteur, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$docteur->getId(), $request->request->get('_token'))) {
            $em->remove($docteur);
            $em->flush();
        }

        return $this->redirectToRoute('list_docteurs');
    }
}
