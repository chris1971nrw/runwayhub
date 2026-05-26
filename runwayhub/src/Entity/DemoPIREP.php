<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'demo_pireps')]
#[ORM\HasLifecycleCallbacks]
class DemoPIREP
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

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 35000])]
    private ?int $altitude = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 450])]
    private ?int $speed = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $fuel = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 1, nullable: false, options: ['default' => 0])]
    private ?float $temperature = null;

    #[ORM\Column(type: 'string', length: 50, nullable: false, options: ['default' => 'Clear'])]
    private ?string $weather_conditions = null;

    #[ORM\Column(type: 'string', length: 10, nullable: false, options: ['default' => '>10nm'])]
    private ?string $visibility = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => '10kt'])]
    private ?string $wind_speed = null;

    #[ORM\Column(type: 'string', length: 10, nullable: false, options: ['default' => 'NW'])]
    private ?string $wind_direction = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'Light'])]
    private ?string $turbulence = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'None'])]
    private ?string $icing = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => true])]
    private ?bool $is_public = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'submitted'])]
    private ?string $status = null;

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

    public function getAltitude(): ?int
    {
        return $this->altitude;
    }

    public function setAltitude(?int $altitude): static
    {
        $this->altitude = $altitude;
        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(?int $speed): static
    {
        $this->speed = $speed;
        return $this;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(?string $fuel): static
    {
        $this->fuel = $fuel;
        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): static
    {
        $this->temperature = $temperature;
        return $this;
    }

    public function getWeatherConditions(): ?string
    {
        return $this->weather_conditions;
    }

    public function setWeatherConditions(?string $weather_conditions): static
    {
        $this->weather_conditions = $weather_conditions;
        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(?string $visibility): static
    {
        $this->visibility = $visibility;
        return $this;
    }

    public function getWindSpeed(): ?string
    {
        return $this->wind_speed;
    }

    public function setWindSpeed(?string $wind_speed): static
    {
        $this->wind_speed = $wind_speed;
        return $this;
    }

    public function getWindDirection(): ?string
    {
        return $this->wind_direction;
    }

    public function setWindDirection(?string $wind_direction): static
    {
        $this->wind_direction = $wind_direction;
        return $this;
    }

    public function getTurbulence(): ?string
    {
        return $this->turbulence;
    }

    public function setTurbulence(?string $turbulence): static
    {
        $this->turbulence = $turbulence;
        return $this;
    }

    public function getIcing(): ?string
    {
        return $this->icing;
    }

    public function setIcing(?string $icing): static
    {
        $this->icing = $icing;
        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;
        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->is_public;
    }

    public function setIsPublic(?bool $is_public): static
    {
        $this->is_public = $is_public;
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
