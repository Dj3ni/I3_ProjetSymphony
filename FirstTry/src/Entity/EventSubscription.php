<?php

namespace App\Entity;

use App\HydrateTrait\HydrateTrait;
use App\Repository\EventSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventSubscriptionRepository::class)]
class EventSubscription
{
    use HydrateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $subscriptionDate = null;

    #[ORM\ManyToOne(inversedBy: 'eventSubscriptions')]
    private ?User $userSubscriptor = null;

    #[ORM\ManyToOne(inversedBy: 'Subscriptions')]
    private ?Event $eventSubscripted = null;

    #[ORM\Column]
    private ?int $numberParticipants = null;

    public function __construct(array $init =[]){
        $this->hydrate($init);
    }
    
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriptionDate(): ?\DateTimeImmutable
    {
        return $this->subscriptionDate;
    }

    public function setSubscriptionDate(\DateTimeImmutable $subscriptionDate): static
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    public function getUserSubscriptor(): ?User
    {
        return $this->userSubscriptor;
    }

    public function setUserSubscriptor(?User $userSubscriptor): static
    {
        $this->userSubscriptor = $userSubscriptor;

        return $this;
    }

    public function getEventSubscripted(): ?Event
    {
        return $this->eventSubscripted;
    }

    public function setEventSubscripted(?Event $eventSubscripted): static
    {
        $this->eventSubscripted = $eventSubscripted;

        return $this;
    }

    public function getNumberParticipants(): ?int
    {
        return $this->numberParticipants;
    }

    public function setNumberParticipants(int $numberParticipants): static
    {
        $this->numberParticipants = $numberParticipants;

        return $this;
    }
}
