<?php

namespace App\Repository;

use App\Entity\DemoUser;
use App\Entity\DemoAirline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemoUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoUser[]    findAll()
 * @method DemoUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemoUser::class);
    }

    /**
     * Erstellt alle Demo-Daten für Users
     */
    public function up(DemoAirline $airline): void
    {
        // Demo Admin
        $admin = new DemoUser();
        $admin->setUsername('demo_admin');
        $admin->setEmail('admin@demofly.runwayhub.de');
        $admin->setPassword('$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); // Hash für 'demo123'
        $admin->setFullName('Demo Administrator');
        $admin->setRole('admin');
        $admin->setIsActive(true);
        $admin->setAirline($airline);
        $this->save($admin);

        // Demo Staff
        $staff = new DemoUser();
        $staff->setUsername('demo_staff');
        $staff->setEmail('staff@demofly.runwayhub.de');
        $staff->setPassword('$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
        $staff->setFullName('Demo Staff Member');
        $staff->setRole('staff');
        $staff->setIsActive(true);
        $staff->setAirline($airline);
        $this->save($staff);

        // Demo Pilot
        $pilot = new DemoUser();
        $pilot->setUsername('demo_pilot');
        $pilot->setEmail('pilot@demofly.runwayhub.de');
        $pilot->setPassword('$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
        $pilot->setFullName('Demo Pilot');
        $pilot->setRole('pilot');
        $pilot->setIsActive(true);
        $pilot->setTypeRating(['737', 'A320', 'B737']);
        $pilot->setCallsign('DMF123');
        $pilot->setAirline($airline);
        $this->save($pilot);

        // Demo Guest
        $guest = new DemoUser();
        $guest->setUsername('demo_guest');
        $guest->setEmail('guest@demofly.runwayhub.de');
        $guest->setPassword('$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
        $guest->setFullName('Demo Gast');
        $guest->setRole('guest');
        $guest->setIsActive(true);
        $guest->setAirline($airline);
        $this->save($guest);
    }

    /**
     * Löscht alle Demo-Daten für Users
     */
    public function deleteAll(): void
    {
        $em = $this->getEntityManager();
        $em->createQuery('DELETE FROM App\Entity\DemoUser')->execute();
    }

    /**
     * Speichert einen Demo User
     */
    public function save(DemoUser $entity, ?bool $flush = null): void
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
