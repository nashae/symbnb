<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifeCycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention la date d'arrivée doit être au bon format")
     * @Assert\GreaterThan("today", message="La date d'arrivée doit etre ultérieure à la date d'aujourd'hui", groups={"front"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention la date de départ doit être au bon format")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date de départ doit être après la date d'arrivée")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Callback appelé à chaque fois qu'on crée une reservation
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function prePersist()
    {
        if(empty($this->createdAt)){
            $this->createdAt = new \DateTime();
        }
        if(empty($this->amount)){
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function isBookableDates()
    {
        //on recupere les jours déjà réservé grace à la fonction getNotAvailableDays crée dans Ad
        $notAvailableDays = $this->ad->getNotAvailableDays();
        //on compare les dates choisies avec les dates déjà reservées
        $bookingDays = $this->getDays();

        //on tranforme le tableau des jours choisis en tableau sous forme de string
        $days = array_map(function($day){
            return $day->format('Y-m-d');
        }, $bookingDays);

        //idem pour le tableau des jours non disponibles
        $notAvailable = array_map(function($day){
            return $day->format('Y-m-d');
        }, $notAvailableDays);
        //on cherhce pour chaque journée si elles sont dans le tableau $notAvailable
        foreach($days as $day){
            //si correspondance array_search retourne le day , donc if retourne false
            if(array_search($day, $notAvailable) !== false) return false;
        }
        return true;
    }

    /**
     * Permet de récupérer un tableau des journées qui correspondent à ma réservation
     *
     * @return array Un tableau d'objet Datetime réprésentant les jours de la réservation
     */
    public function getDays()
    {
        $resultat = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 * 60 * 60
        );
        $days = array_map(function($dayTimestamp){
            return new DateTime(date('Y-m-d', $dayTimestamp));
        }, $resultat);
        return $days;
    }

    public function getDuration()
    {
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
