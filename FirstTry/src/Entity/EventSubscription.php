<?php

namespace App\Entity;

use App\HydrateTrait\HydrateTrait;
use App\Repository\EventSubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventSubscriptionRepository::class)]
class EventSubscription
{

########################## Imports #########################################

    use HydrateTrait;

########################## Properties #########################################

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $subscriptionDate = null;
    


    // #[ORM\Column]
    // private array $occurrenceDates = [];

#################### Relations ###################################################

    #[ORM\ManyToOne(inversedBy: 'eventSubscriptions')]
    private ?User $userSubscriptor = null;

    /**
     * @var Collection<int, EventOccurrence>
     */
    #[ORM\ManyToMany(targetEntity: EventOccurrence::class, mappedBy: 'subscriptions')]
    private Collection $eventOccurrences;

    // #[ORM\ManyToOne(inversedBy: 'Subscriptions')]
    // private ?Event $eventSubscripted = null;



#####################  Functions #########################################


    public function __construct(array $init =[]){
        $this->hydrate($init);
        $this->eventOccurrences = new ArrayCollection();
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

    // public function getEventSubscripted(): ?Event
    // {
    //     return $this->eventSubscripted;
    // }

    // public function setEventSubscripted(?Event $eventSubscripted): static
    // {
    //     $this->eventSubscripted = $eventSubscripted;

    //     return $this;
    // }


    // public function getOccurrenceDates(): array
    // {
    //     return $this->occurrenceDates;
    // }

    // public function setOccurrenceDates(array $occurrenceDates): static
    // {
    //     $this->occurrenceDates = $occurrenceDates;

    //     return $this;
    // }

    /**
     * @return Collection<int, EventOccurrence>
     */
    public function getEventOccurrences(): Collection
    {
        return $this->eventOccurrences;
    }

    public function addEventOccurrence(EventOccurrence $eventOccurrence): static
    {
        if (!$this->eventOccurrences->contains($eventOccurrence)) {
            $this->eventOccurrences->add($eventOccurrence);
            $eventOccurrence->addSubscription($this);
        }

        return $this;
    }

    public function removeEventOccurrence(EventOccurrence $eventOccurrence): static
    {
        if ($this->eventOccurrences->removeElement($eventOccurrence)) {
            $eventOccurrence->removeSubscription($this);
        }

        return $this;
    }



}
