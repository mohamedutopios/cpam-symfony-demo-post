<?php

namespace App\Twig\Components;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent]
class FormationLiveCounter
{
    use DefaultActionTrait;

    #LiveProp
    public int $totalParticipants;

    public function __construct(private FormationRepository $formationRepository)
    {
        $this->updateTotalParticipants();
    }

    public function updateTotalParticipants(): void
    {
        $this->totalParticipants = $this->formationRepository->countTotalParticipants();
    }
}