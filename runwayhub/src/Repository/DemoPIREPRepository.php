<?php

namespace App\Repository;

use App\Entity\DemoPIREP;
use App\Entity\DemoAircraft;
use App\Entity\DemoUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemoPIREP|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoPIREP|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoPIREP[]    findAll()
 * @method DemoPIREP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoPIREPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemoPIREP::class);
    }

    /**
     * Erstellt alle Demo-Daten für PIREPs
     */
    public function up(DemoAircraft $boeing, DemoUser $pilot, DemoPIREP $pirep): void
    {
        $pirep->setAircraft($boeing);
        $pirep->setPilot($pilot);
        $this->save($pirep);
    }

    /**
     * Löscht alle Demo-Daten für PIREPs
     */
    public function deleteAll(): void
    {
        $em = $this->getEntityManager();
        $em->createQuery('DELETE FROM App\Entity\DemoPIREP')->execute();
    }

    /**
     * Speichert einen Demo PIREP
     */
    public function save(DemoPIREP $entity, ?bool $flush = null): void
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
