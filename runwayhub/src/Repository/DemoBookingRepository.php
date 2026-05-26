<?php

namespace App\Repository;

use App\Entity\DemoBooking;
use App\Entity\DemoFlight;
use App\Entity\DemoUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemoBooking|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoBooking|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoBooking[]    findAll()
 * @method DemoBooking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoBookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemoBooking::class);
    }

    /**
     * Erstellt alle Demo-Daten für Bookings
     */
    public function up(DemoFlight $flight, DemoUser $pilot): void
    {
        // Buchung 1
        $booking1 = new DemoBooking();
        $booking1->setFlight($flight);
        $booking1->setBookingNumber('DM001');
        $booking1->setPassengerName('Max Mustermann');
        $booking1->setPassengerEmail('max@example.com');
        $booking1->setPassengerType('adult');
        $booking1->setClass('economy');
        $booking1->setPrice(299.00);
        $booking1->setTax(15.00);
        $booking1->setTotal(314.00);
        $booking1->setPaymentMethod('credit_card');
        $booking1->setPaymentStatus('paid');
        $booking1->setStatus('confirmed');
        $this->save($booking1);

        // Buchung 2
        $booking2 = new DemoBooking();
        $booking2->setFlight($flight);
        $booking2->setBookingNumber('DM002');
        $booking2->setPassengerName('Erika Musterfrau');
        $booking2->setPassengerEmail('erika@example.com');
        $booking2->setPassengerType('adult');
        $booking2->setClass('economy');
        $booking2->setPrice(299.00);
        $booking2->setTax(15.00);
        $booking2->setTotal(314.00);
        $booking2->setPaymentMethod('credit_card');
        $booking2->setPaymentStatus('paid');
        $booking2->setStatus('confirmed');
        $this->save($booking2);

        // Buchung 3
        $booking3 = new DemoBooking();
        $booking3->setFlight($flight);
        $booking3->setBookingNumber('DM003');
        $booking3->setPassengerName('Hans Beispielmann');
        $booking3->setPassengerEmail('hans@example.com');
        $booking3->setPassengerType('adult');
        $booking3->setClass('economy');
        $booking3->setPrice(299.00);
        $booking3->setTax(15.00);
        $booking3->setTotal(314.00);
        $booking3->setPaymentMethod('paypal');
        $booking3->setPaymentStatus('paid');
        $booking3->setStatus('confirmed');
        $this->save($booking3);
    }

    /**
     * Löscht alle Demo-Daten für Bookings
     */
    public function deleteAll(): void
    {
        $em = $this->getEntityManager();
        $em->createQuery('DELETE FROM App\Entity\DemoBooking')->execute();
    }

    /**
     * Speichert eine Demo Booking
     */
    public function save(DemoBooking $entity, ?bool $flush = null): void
    {
        $entity->setCreatedAt(new \DateTimeImmutable());
        $entity->setUpdatedAt(new \DateTimeImmutable());
        $em = $this->getEntityManager();
        $em->persist($entity);
        if ($flush) {
            $em->flush();
        }
    }
}
