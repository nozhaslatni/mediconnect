<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appointment')]
class AppointmentController extends AbstractController
{
    // -------------------------------
    // LIST ALL APPOINTMENTS
    // -------------------------------
    #[Route('/', name: 'appointment_index', methods: ['GET'])]
    public function index(AppointmentRepository $repo): Response
    {
        return $this->render('appointment/index.html.twig', [
            'appointments' => $repo->findAll(),
        ]);
    }

    // -------------------------------
    // CREATE APPOINTMENT (PATIENT)
    // -------------------------------
    #[Route('/new', name: 'appointment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($appointment);
            $em->flush();

            return $this->redirectToRoute('appointment_index');
        }

        return $this->render('appointment/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // -------------------------------
    // EDIT APPOINTMENT
    // -------------------------------
    #[Route('/edit/{id}', name: 'appointment_edit', methods: ['GET','POST'])]
    public function edit(
        Request $request,
        Appointment $appointment,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Appointment updated!');
            return $this->redirectToRoute('appointment_index');
        }

        return $this->render('appointment/edit.html.twig', [
            'form' => $form->createView(),
            'appointment' => $appointment,
        ]);
    }

    // -------------------------------
    // DELETE APPOINTMENT
    // -------------------------------
    #[Route('/delete/{id}', name: 'appointment_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Appointment $appointment,
        EntityManagerInterface $em
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $em->remove($appointment);
            $em->flush();
        }

        return $this->redirectToRoute('appointment_index');
    }

    // ------------------------------------
    // DOCTOR ACTIONS : ACCEPT APPOINTMENT
    // ------------------------------------
    #[Route('/accept/{id}', name: 'appointment_accept', methods: ['POST'])]
    public function accept(Appointment $appointment, EntityManagerInterface $em): Response
    {
        $appointment->setStatus('accepted');
        $em->flush();

        $this->addFlash('success', 'Appointment accepted!');
        return $this->redirectToRoute('appointment_index');
    }

    // ------------------------------------
    // DOCTOR REFUSE
    // ------------------------------------
    #[Route('/refuse/{id}', name: 'appointment_refuse', methods: ['POST'])]
    public function refuse(Appointment $appointment, EntityManagerInterface $em): Response
    {
        $appointment->setStatus('refused');
        $em->flush();

        $this->addFlash('error', 'Appointment refused.');
        return $this->redirectToRoute('appointment_index');
    }

    // ------------------------------------
    // DOCTOR MARK AS FINISHED
    // ------------------------------------
    #[Route('/finish/{id}', name: 'appointment_finish', methods: ['POST'])]
    public function finish(Appointment $appointment, EntityManagerInterface $em): Response
    {
        $appointment->setStatus('finished');
        $em->flush();

        $this->addFlash('success', 'Appointment marked as finished.');
        return $this->redirectToRoute('appointment_index');
    }
}
