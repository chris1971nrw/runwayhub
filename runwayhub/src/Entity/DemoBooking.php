<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'demo_bookings')]
#[ORM\HasLifecycleCallbacks]
class DemoBooking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: DemoFlight::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'flight_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    private ?DemoFlight $flight = null;

    #[ORM\ManyToOne(targetEntity: DemoUser::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?DemoUser $user = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    private ?string $booking_number = null;

    #[ORM\ManyToOne(targetEntity: DemoUser::class, fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?DemoUser $passenger = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $passenger_name = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $passenger_email = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'adult'])]
    private ?string $passenger_type = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'economy'])]
    private ?string $class = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private ?float $price = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false, options: ['default' => 0])]
    private ?float $tax = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private ?float $total = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'credit_card'])]
    private ?string $payment_method = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'pending'])]
    private ?string $payment_status = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $transaction_id = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'pending'])]
    private ?string $status = null;

    #[ORM\Column(type: 'datetime', name: 'created_at')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', name: 'updated_at')]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlight(): ?DemoFlight
    {
        return $this->flight;
    }

    public function setFlight(?DemoFlight $flight): static
    {
        $this->flight = $flight;
        return $this;
    }

    public function getUser(): ?DemoUser
    {
        return $this->user;
    }

    public function setUser(?DemoUser $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getBookingNumber(): ?string
    {
        return $this->booking_number;
    }

    public function setBookingNumber(?string $booking_number): static
    {
        $this->booking_number = $booking_number;
        return $this;
    }

    public function getPassenger(): ?DemoUser
    {
        return $this->passenger;
    }

    public function setPassenger(?DemoUser $passenger): static
    {
        $this->passenger = $passenger;
        return $this;
    }

    public function getPassengerName(): ?string
    {
        return $this->passenger_name;
    }

    public function setPassengerName(?string $passenger_name): static
    {
        $this->passenger_name = $passenger_name;
        return $this;
    }

    public function getPassengerEmail(): ?string
    {
        return $this->passenger_email;
    }

    public function setPassengerEmail(?string $passenger_email): static
    {
        $this->passenger_email = $passenger_email;
        return $this;
    }

    public function getPassengerType(): ?string
    {
        return $this->passenger_type;
    }

    public function setPassengerType(?string $passenger_type): static
    {
        $this->passenger_type = $passenger_type;
        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): static
    {
        $this->class = $class;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(?float $tax): static
    {
        $this->tax = $tax;
        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): static
    {
        $this->total = $total;
        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(?string $payment_method): static
    {
        $this->payment_method = $payment_method;
        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->payment_status;
    }

    public function setPaymentStatus(?string $payment_status): static
    {
        $this->payment_status = $payment_status;
        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transaction_id;
    }

    public function setTransactionId(?string $transaction_id): static
    {
        $this->transaction_id = $transaction_id;
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
