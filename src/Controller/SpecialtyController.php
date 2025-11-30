<?php
namespace App\Controller;

use App\Entity\Specialty;
use App\Form\SpecialtyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/specialty')]
class SpecialtyController extends AbstractController
{
#[Route('/', name: 'specialty_index', methods: ['GET'])]
public function index(EntityManagerInterface $em): Response
{
return $this->render('specialty/index.html.twig', [
'specialties' => $em->getRepository(Specialty::class)->findAll(),
]);
}

#[Route('/new', name: 'specialty_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $em): Response
{
$specialty = new Specialty();
$form = $this->createForm(SpecialtyType::class, $specialty);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$em->persist($specialty);
$em->flush();

return $this->redirectToRoute('specialty_index');
}

return $this->render('specialty/new.html.twig', [
'form' => $form->createView(),
]);
}
}
