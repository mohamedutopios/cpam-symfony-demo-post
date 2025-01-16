<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormationController extends AbstractController
{
    public function __construct(
        private FormationRepository $formationRepository,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'formation_list', methods: ['GET'])]
    public function index(): Response
    {
       
        $formations = $this->formationRepository->findAll();
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }


    #[Route('/formation/{id}/subscribe', name: 'formation_subscribe', methods: ['POST'])]
    public function subscribe(int $id): RedirectResponse
    {
        $formation = $this->formationRepository->find($id);

        if ($formation) {
            $formation->incrementParticipants();
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('formation_list');
    }

    #[Route('/formation/{id}/unsubscribe', name: 'formation_unsubscribe', methods: ['POST'])]
    public function unsubscribe(int $id): RedirectResponse
    {
        $formation = $this->formationRepository->find($id);

        if ($formation) {
            $formation->decrementParticipants();
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('formation_list');
    }
}