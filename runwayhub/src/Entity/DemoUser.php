<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Text;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'demo_users')]
#[ORM\HasLifecycleCallbacks]
class DemoUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50, nullable: false, unique: true)]
    private ?string $username = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $full_name = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'guest'])]
    private ?string $role = null;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => true])]
    private ?bool $is_active = null;

    #[ORM\ManyToOne(targetEntity: DemoAirline::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'airline_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?DemoAirline $airline = null;

    #[ORM\Column(type: 'datetime', name: 'created_at')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', name: 'updated_at')]
    private ?\DateTimeInterface $updatedAt = null;

    // Pilot-spezifische Felder
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $type_rating = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $callsign = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(?string $full_name): static
    {
        $this->full_name = $full_name;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;
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

    public function getTypeRating(): ?array
    {
        return $this->type_rating;
    }

    public function setTypeRating(?array $type_rating): static
    {
        $this->type_rating = $type_rating;
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
}
