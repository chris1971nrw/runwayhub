<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Text;

#[ORM\Entity]
#[ORM\Table(name: 'demo_airlines')]
#[ORM\HasLifecycleCallbacks]
class DemoAirline
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 10, nullable: false)]
    private ?string $code = null;

    #[ORM\Column(type: 'string', length: 2, nullable: false)]
    private ?string $iata = null;

    #[ORM\Column(type: 'string', length: 4, nullable: false)]
    private ?string $icao = null;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    private ?string $callsign = null;

    #[ORM\Column(type: 'string', length: 2, nullable: false)]
    private ?string $country = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => '#0066cc'])]
    private ?string $color = null;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => true])]
    private ?bool $is_active = null;

    #[ORM\Column(type: 'datetime', name: 'created_at')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', name: 'updated_at')]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getIata(): ?string
    {
        return $this->iata;
    }

    public function setIata(?string $iata): static
    {
        $this->iata = $iata;
        return $this;
    }

    public function getIcao(): ?string
    {
        return $this->icao;
    }

    public function setIcao(?string $icao): static
    {
        $this->icao = $icao;
        return $this;
    }

    public function getCallsign(): ?string
    {
        return $this->callsign;
    }

    public function setCallsign(?string $callsign): static
    {
        $this->callsign = $callsign;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(?bool $is_active): static
    {
        $this->is_active = $is_active;
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
