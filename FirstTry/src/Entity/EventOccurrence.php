<?php

namespace App\Entity;

use App\HydrateTrait\HydrateTrait;
use App\Repository\EventOccurrenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventOccurrenceRepository::class)]
class EventOccurrence
{
    use HydrateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    ###################### Relations

    #[ORM\ManyToOne(inversedBy: 'Occurrences')]
    private ?Event $event = null;

    /**
     * @var Collection<int, EventSubscription>
     */
    #[ORM\ManyToMany(targetEntity: EventSubscription::class, inversedBy: 'eventOccurrences', cascade:['persist', 'remove'])]
    private Collection $subscriptions;



    ####################### Functions

    public function __construct(array $init = [])
    {
        $this->hydrate($init);
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Collection<int, EventSubscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(EventSubscription $subscription): static
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->addEventOccurrence($this);

        }

        return $this;
    }

    public function removeSubscription(EventSubscription $subscription): static
    {
        if ($this->subscriptions->removeElement($subscription)){
            // delete relation between subscription and occurrence
            if ($subscription->getEventOccurrences() === $this) {
                $subscription->removeEventOccurrence($this);
            }
        };

        return $this;
    }


}
