<?php

namespace App\Repository;

use App\Entity\DemoAirline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemoAirline|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoAirline|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoAirline[]    findAll()
 * @method DemoAirline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoAirlineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemoAirline::class);
    }

    /**
     * Erstellt alle Demo-Daten für Airline
     */
    public function up(): void
    {
        $airline = new DemoAirline();
        $airline->setName('DemoFly');
        $airline->setCode('DMO');
        $airline->setIata('DM');
        $airline->setIcao('DMFLY');
        $airline->setCallsign('DEMOFLY');
        $airline->setCountry('DE');
        $airline->setLogo(null);
        $airline->setColor('#0066cc');
        $airline->setIsActive(true);

        $this->save($airline);
    }

    /**
     * Löscht alle Demo-Daten für Airline
     */
    public function deleteAll(): void
    {
        $em = $this->getEntityManager();
        $em->createQuery('DELETE FROM App\Entity\DemoAirline')->execute();
    }

    /**
     * Speichert eine Demo Airline
     */
    public function save(DemoAirline $entity, ?bool $flush = null): void
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
