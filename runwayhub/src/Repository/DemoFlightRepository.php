<?php

namespace App\Repository;

use App\Entity\DemoFlight;
use App\Entity\DemoAircraft;
use App\Entity\DemoUser;
use App\Entity\DemoAirline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemoFlight|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoFlight|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoFlight[]    findAll()
 * @method DemoFlight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoFlightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemoFlight::class);
    }

    /**
     * Erstellt alle Demo-Daten für Flüge
     */
    public function up(
        DemoAirline $airline,
        DemoAircraft $boeing,
        DemoUser $pilot,
        DemoFlight $flight
    ): void {
        $flight->setAircraft($boeing);
        $flight->setPilot($pilot);
        $flight->setAirline($airline);
        $this->save($flight);
    }

    /**
     * Löscht alle Demo-Daten für Flüge
     */
    public function deleteAll(): void
    {
        $em = $this->getEntityManager();
        $em->createQuery('DELETE FROM App\Entity\DemoFlight')->execute();
    }

    /**
     * Speichert einen Demo Flight
     */
    public function save(DemoFlight $entity, ?bool $flush = null): void
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
