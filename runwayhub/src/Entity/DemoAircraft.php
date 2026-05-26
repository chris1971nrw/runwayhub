<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'demo_aircraft')]
#[ORM\HasLifecycleCallbacks]
class DemoAircraft
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    private ?string $type = null;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    private ?string $model = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, unique: true)]
    private ?string $registration = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false, options: ['default' => 'Demo'])]
    private ?string $manufacturer = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 2024])]
    private ?int $year = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 150])]
    private ?int $seats = null;

    #[ORM\Column(type: 'string', length: 100, nullable: false, options: ['default' => 'Demo'])]
    private ?string $engine = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'Jet A'])]
    private ?string $fuel = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 4000])]
    private ?int $range = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 41000])]
    private ?int $max_altitude = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'active'])]
    private ?string $status = null;

    #[ORM\Column(type: 'datetime', name: 'last_maintenance', nullable: true)]
    private ?\DateTimeInterface $lastMaintenance = null;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 500])]
    private ?int $maintenance_interval = null;

    #[ORM\ManyToOne(targetEntity: DemoAirline::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'airline_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?DemoAirline $airline = null;

    #[ORM\Column(type: 'datetime', name: 'created_at')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', name: 'updated_at')]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): static
    {
        $this->model = $model;
        return $this;
    }

    public function getRegistration(): ?string
    {
        return $this->registration;
    }

    public function setRegistration(?string $registration): static
    {
        $this->registration = $registration;
        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer): static
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(?int $seats): static
    {
        $this->seats = $seats;
        return $this;
    }

    public function getEngine(): ?string
    {
        return $this->engine;
    }

    public function setEngine(?string $engine): static
    {
        $this->engine = $engine;
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

    public function getRange(): ?int
    {
        return $this->range;
    }

    public function setRange(?int $range): static
    {
        $this->range = $range;
        return $this;
    }

    public function getMaxAltitude(): ?int
    {
        return $this->max_altitude;
    }

    public function setMaxAltitude(?int $max_altitude): static
    {
        $this->max_altitude = $max_altitude;
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

    public function getLastMaintenance(): ?\DateTimeInterface
    {
        return $this->lastMaintenance;
    }

    public function setLastMaintenance(?\DateTimeInterface $lastMaintenance): static
    {
        $this->lastMaintenance = $lastMaintenance;
        return $this;
    }

    public function getMaintenanceInterval(): ?int
    {
        return $this->maintenance_interval;
    }

    public function setMaintenanceInterval(?int $maintenance_interval): static
    {
        $this->maintenance_interval = $maintenance_interval;
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
