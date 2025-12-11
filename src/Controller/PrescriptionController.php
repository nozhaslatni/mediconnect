<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Form\PrescriptionType;
use App\Repository\PrescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prescription')]
final class PrescriptionController extends AbstractController
{
    // Liste : GET /prescription
    #[Route('', name: 'app_prescription_index', methods: ['GET'])]
    public function index(PrescriptionRepository $repo): Response
    {
        return $this->render('prescription/index.html.twig', [
            'prescriptions' => $repo->findAll(),
        ]);
    }

    // Nouveau : GET /prescription/new + POST /prescription/new
    #[Route('/new', name: 'app_prescription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $prescription = new Prescription();
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($prescription);
            $em->flush();

            return $this->redirectToRoute('app_prescription_index');
        }

        return $this->render('prescription/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Détail : GET /prescription/{id}
    #[Route('/{id}', name: 'app_prescription_show', methods: ['GET'])]
    public function show(Prescription $prescription): Response
    {
        return $this->render('prescription/show.html.twig', [
            'prescription' => $prescription,
        ]);
    }

    // Edition : GET+POST /prescription/{id}/edit
    #[Route('/{id}/edit', name: 'app_prescription_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prescription $prescription, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_prescription_index');
        }

        return $this->render('prescription/edit.html.twig', [
            'prescription' => $prescription,
            'form' => $form->createView(),
        ]);
    }

    // Suppression : POST /prescription/{id}
    #[Route('/{id}', name: 'app_prescription_delete', methods: ['POST'])]
    public function delete(Request $request, Prescription $prescription, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prescription->getId(), $request->request->get('_token'))) {
            $em->remove($prescription);
            $em->flush();
        }

        return $this->redirectToRoute('app_prescription_index');
    }
}
