<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'demo_flights')]
#[ORM\HasLifecycleCallbacks]
class DemoFlight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    private ?string $flight_number = null;

    #[ORM\ManyToOne(targetEntity: DemoAircraft::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'aircraft_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?DemoAircraft $aircraft = null;

    #[ORM\ManyToOne(targetEntity: DemoUser::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'pilot_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?DemoUser $pilot = null;

    #[ORM\ManyToOne(targetEntity: DemoAirline::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'airline_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?DemoAirline $airline = null;

    #[ORM\Column(type: 'string', length: 10, nullable: false)]
    private ?string $airport_from = null;

    #[ORM\Column(type: 'string', length: 10, nullable: false)]
    private ?string $airport_to = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $route = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 600])]
    private ?int $distance = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 90])]
    private ?int $duration = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $scheduled_date = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?string $scheduled_time = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $departure_time = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $arrival_time = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $eta = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $eot = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'scheduled'])]
    private ?string $status = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false, options: ['default' => 299.00])]
    private ?float $price_economy = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false, options: ['default' => 599.00])]
    private ?float $price_business = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false, options: ['default' => 1299.00])]
    private ?float $price_first = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 100])]
    private ?int $available_seats = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0])]
    private ?int $sold_seats = null;

    #[ORM\Column(type: 'datetime', name: 'created_at')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', name: 'updated_at')]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlightNumber(): ?string
    {
        return $this->flight_number;
    }

    public function setFlightNumber(?string $flight_number): static
    {
        $this->flight_number = $flight_number;
        return $this;
    }

    public function getAircraft(): ?DemoAircraft
    {
        return $this->aircraft;
    }

    public function setAircraft(?DemoAircraft $aircraft): static
    {
        $this->aircraft = $aircraft;
        return $this;
    }

    public function getPilot(): ?DemoUser
    {
        return $this->pilot;
    }

    public function setPilot(?DemoUser $pilot): static
    {
        $this->pilot = $pilot;
        return $this;
    }

    public function getAirline(): ?DemoAirline
    {
        return $this->airline;
    }

    public function setAirline(?DemoAirline $airline): static
    {
        $this->airline = $airline;
        return $this;
    }

    public function getAirportFrom(): ?string
    {
        return $this->airport_from;
    }

    public function setAirportFrom(?string $airport_from): static
    {
        $this->airport_from = $airport_from;
        return $this;
    }

    public function getAirportTo(): ?string
    {
        return $this->airport_to;
    }

    public function setAirportTo(?string $airport_to): static
    {
        $this->airport_to = $airport_to;
        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): static
    {
        $this->route = $route;
        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(?int $distance): static
    {
        $this->distance = $distance;
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;
        return $this;
    }

    public function getScheduledDate(): ?\DateTimeInterface
    {
        return $this->scheduled_date;
    }

    public function setScheduledDate(?\DateTimeInterface $scheduled_date): static
    {
        $this->scheduled_date = $scheduled_date;
        return $this;
    }

    public function getScheduledTime(): ?string
    {
        return $this->scheduled_time;
    }

    public function setScheduledTime(?string $scheduled_time): static
    {
        $this->scheduled_time = $scheduled_time;
        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departure_time;
    }

    public function setDepartureTime(?\DateTimeInterface $departure_time): static
    {
        $this->departure_time = $departure_time;
        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrival_time;
    }

    public function setArrivalTime(?\DateTimeInterface $arrival_time): static
    {
        $this->arrival_time = $arrival_time;
        return $this;
    }

    public function getEta(): ?\DateTimeInterface
    {
        return $this->eta;
    }

    public function setEta(?\DateTimeInterface $eta): static
    {
        $this->eta = $eta;
        return $this;
    }

    public function getEot(): ?\DateTimeInterface
    {
        return $this->eot;
    }

    public function setEot(?\DateTimeInterface $eot): static
    {
        $this->eot = $eot;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getPriceEconomy(): ?float
    {
        return $this->price_economy;
    }

    public function setPriceEconomy(?float $price_economy): static
    {
        $this->price_economy = $price_economy;
        return $this;
    }

    public function getPriceBusiness(): ?float
    {
        return $this->price_business;
    }

    public function setPriceBusiness(?float $price_business): static
    {
        $this->price_business = $price_business;
        return $this;
    }

    public function getPriceFirst(): ?float
    {
        return $this->price_first;
    }

    public function setPriceFirst(?float $price_first): static
    {
        $this->price_first = $price_first;
        return $this;
    }

    public function getAvailableSeats(): ?int
    {
        return $this->available_seats;
    }

    public function setAvailableSeats(?int $available_seats): static
    {
        $this->available_seats = $available_seats;
        return $this;
    }

    public function getSoldSeats(): ?int
    {
        return $this->sold_seats;
    }

    public function setSoldSeats(?int $sold_seats): static
    {
        $this->sold_seats = $sold_seats;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
