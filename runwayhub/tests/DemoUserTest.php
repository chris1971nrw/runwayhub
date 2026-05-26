<?php

namespace App\Tests;

use App\Entity\DemoUser;
use PHPUnit\Framework\TestCase;

/**
 * Demo User Test Suite
 */
class DemoUserTest extends TestCase
{
    /**
     * Test: Demo User Admin wird erstellt
     */
    public function testDemoAdminIsCreated(): void
    {
        $admin = new DemoUser();
        $admin->setUsername('demo_admin');
        $admin->setEmail('admin@demofly.runwayhub.de');
        $admin->setPassword('$2y$10$...');
        $admin->setFullName('Demo Administrator');
        $admin->setRole('admin');
        $admin->setIsActive(true);

        $this->assertEquals('demo_admin', $admin->getUsername());
        $this->assertEquals('admin@demofly.runwayhub.de', $admin->getEmail());
        $this->assertEquals('admin', $admin->getRole());
    }

    /**
     * Test: Demo User Staff wird erstellt
     */
    public function testDemoStaffIsCreated(): void
    {
        $staff = new DemoUser();
        $staff->setUsername('demo_staff');
        $staff->setEmail('staff@demofly.runwayhub.de');
        $staff->setPassword('$2y$10$...');
        $staff->setFullName('Demo Staff Member');
        $staff->setRole('staff');
        $staff->setIsActive(true);

        $this->assertEquals('demo_staff', $staff->getUsername());
        $this->assertEquals('staff', $staff->getRole());
    }

    /**
     * Test: Demo User Pilot wird erstellt
     */
    public function testDemoPilotIsCreated(): void
    {
        $pilot = new DemoUser();
        $pilot->setUsername('demo_pilot');
        $pilot->setEmail('pilot@demofly.runwayhub.de');
        $pilot->setPassword('$2y$10$...');
        $pilot->setFullName('Demo Pilot');
        $pilot->setRole('pilot');
        $pilot->setIsActive(true);
        $pilot->setTypeRating(['737', 'A320', 'B737']);
        $pilot->setCallsign('DMF123');

        $this->assertEquals('demo_pilot', $pilot->getUsername());
        $this->assertEquals('pilot', $pilot->getRole());
        $this->assertEquals(['737', 'A320', 'B737'], $pilot->getTypeRating());
        $this->assertEquals('DMF123', $pilot->getCallsign());
    }

    /**
     * Test: Demo User Guest wird erstellt
     */
    public function testDemoGuestIsCreated(): void
    {
        $guest = new DemoUser();
        $guest->setUsername('demo_guest');
        $guest->setEmail('guest@demofly.runwayhub.de');
        $guest->setPassword('$2y$10$...');
        $guest->setFullName('Demo Gast');
        $guest->setRole('guest');
        $guest->setIsActive(true);

        $this->assertEquals('demo_guest', $guest->getUsername());
        $this->assertEquals('guest', $guest->getRole());
    }

    /**
     * Test: Demo User Rollen sind korrekt
     */
    public function testDemoUserRoles(): void
    {
        $validRoles = ['admin', 'staff', 'pilot', 'guest'];
        
        foreach ($validRoles as $role) {
            $user = new DemoUser();
            $user->setRole($role);
            
            $this->assertEquals($role, $user->getRole());
        }
    }

    /**
     * Test: Demo User kann ein Telefon haben
     */
    public function testDemoUserHasPhone(): void
    {
        $user = new DemoUser();
        $user->setPhone('+49 123 456789');

        $this->assertEquals('+49 123 456789', $user->getPhone());
    }

    /**
     * Test: Demo User kann ein Avatar haben
     */
    public function testDemoUserHasAvatar(): void
    {
        $user = new DemoUser();
        $user->setAvatar('avatar.jpg');

        $this->assertEquals('avatar.jpg', $user->getAvatar());
    }
}
