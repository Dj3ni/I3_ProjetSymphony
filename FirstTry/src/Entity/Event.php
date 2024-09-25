<?php

namespace App\Entity;

use App\Enum\EventType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\EventSubscription;
use App\Enum\RecurrenceType;
use App\HydrateTrait\HydrateTrait;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
########################## Imports #########################################
    use HydrateTrait;

########################## Properties #########################################
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: 'string', enumType: RecurrenceType::class)] 
    private RecurrenceType $recurrenceType;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $recurrenceEnd = null; // Date à laquelle la récurrence se termine

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $recurrenceCount = null; // Nombre d'occurrences

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $fee = "0.00";

    //We need to instruct Doctrine that we have an Enum otherwise, it doesn't understand
    #[ORM\Column(type: 'string', enumType: EventType::class)] 
    private EventType $eventType;

#################### Relations ###################################################

    /**
     * @var Collection<int, EventSubscription>
     */
    #[ORM\OneToMany(targetEntity: EventSubscription::class, mappedBy: 'eventSubscripted')]
    private Collection $Subscriptions;

    /**
     * @var Collection<int, GamingPlace>
     */
    #[ORM\OneToMany(targetEntity: GamingPlace::class, mappedBy: 'event')]
    private Collection $places;

    
#####################  Functions #########################################
    
    public function __construct(array $init = [])
    {
        $this->hydrate($init);
        $this->Subscriptions = new ArrayCollection();
        $this->places = new ArrayCollection();
    }
    #[Assert\Callback] //will be called when entity validated
    public function isDatesValid(ExecutionContextInterface $context): void
    {
        // Context let us send a persinalized msg and will be checked with isValid in form
        if($this->dateEnd < $this->dateStart){
            $context->buildViolation("Date End must me after Date start")
                    ->atPath("dateEnd") //error field
                    ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFee(): ?string
    {
        return $this->fee;
    }

    public function setFee(string $fee): static
    {
        $this->fee = $fee;

        return $this;
    }

    public function getEventType(): eventType
    {
        return $this->eventType;
    }

    public function setEventType(eventType $eventType): self
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * @return Collection<int, EventSubscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->Subscriptions;
    }

    public function addSubscription(EventSubscription $subscription): static
    {
        if (!$this->Subscriptions->contains($subscription)) {
            $this->Subscriptions->add($subscription);
            $subscription->setEventSubscripted($this);
        }

        return $this;
    }

    public function removeSubscription(EventSubscription $subscription): static
    {
        if ($this->Subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getEventSubscripted() === $this) {
                $subscription->setEventSubscripted(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of recurrenceType
     */
    public function getRecurrenceType(): RecurrenceType
    {
        return $this->recurrenceType;
    }

    /**
     * Set the value of recurrenceType
     */
    public function setRecurrenceType(RecurrenceType $recurrenceType): self
    {
        $this->recurrenceType = $recurrenceType;

        return $this;
    }

    /**
     * Get the value of recurrenceEnd
     */
    public function getRecurrenceEnd(): ?\DateTimeInterface
    {
        return $this->recurrenceEnd;
    }

    /**
     * Set the value of recurrenceEnd
     */
    public function setRecurrenceEnd(?\DateTimeInterface $recurrenceEnd): self
    {
        $this->recurrenceEnd = $recurrenceEnd;

        return $this;
    }

    /**
     * Get the value of recurrenceCount
     */
    public function getRecurrenceCount(): ?int
    {
        return $this->recurrenceCount;
    }

    /**
     * Set the value of recurrenceCount
     */
    public function setRecurrenceCount(?int $recurrenceCount): self
    {
        $this->recurrenceCount = $recurrenceCount;

        return $this;
    }

    /**
     * @return Collection<int, GamingPlace>
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(GamingPlace $place): static
    {
        if (!$this->places->contains($place)) {
            $this->places->add($place);
            $place->setEvent($this);
        }

        return $this;
    }

    public function removePlace(GamingPlace $place): static
    {
        if ($this->places->removeElement($place)) {
            // set the owning side to null (unless already changed)
            if ($place->getEvent() === $this) {
                $place->setEvent(null);
            }
        }

        return $this;
    }
}
