<?php

namespace App\Repository;

use App\Entity\DemoAircraft;
use App\Entity\DemoAirline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemoAircraft|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoAircraft|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoAircraft[]    findAll()
 * @method DemoAircraft[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoAircraftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemoAircraft::class);
    }

    /**
     * Erstellt alle Demo-Daten für Flotte
     */
    public function up(DemoAirline $airline, DemoAircraft $boeing, DemoAircraft $airbus, DemoAircraft $cessna): void
    {
        $boeing->setAirline($airline);
        $this->save($boeing);

        $airbus->setAirline($airline);
        $this->save($airbus);

        $cessna->setAirline($airline);
        $this->save($cessna);
    }

    /**
     * Löscht alle Demo-Daten für Flotte
     */
    public function deleteAll(): void
    {
        $em = $this->getEntityManager();
        $em->createQuery('DELETE FROM App\Entity\DemoAircraft')->execute();
    }

    /**
     * Speichert eine Demo Aircraft
     */
    public function save(DemoAircraft $entity, ?bool $flush = null): void
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
