<?php

namespace App\Console\Command;

use App\Core\OpenAIP\Airport;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DemoAirline;
use App\Entity\DemoUser;
use App\Entity\DemoAircraft;
use App\Entity\DemoFlight;
use App\Entity\DemoPIREP;
use App\Entity\DemoBooking;

#[AsCommand(
    name: 'demo:install',
    description: 'Installiert Demo Airline & User mit allen Demo-Daten',
)]
class DemoInstallCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('reset', null, InputOption::VALUE_NONE, 'Soll alte Demo-Daten gelöscht werden?')
            ->addOption('quiet', null, InputOption::VALUE_NONE, 'Nur wichtige Nachrichten anzeigen');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>=== Demo Data Installation ===</info>');
        
        $quiet = $input->getOption('quiet');
        $reset = $input->getOption('reset');

        if (!$quiet) {
            $output->writeln('<info>Diese Aktion installiert Demo-Daten für RunwayHub.</info>');
            $output->writeln('<info>Alle Installationen werden dokumentiert in: docs/changelog.md</info>');
        }

        // Database connection check
        try {
            $em = EntityManager::create(
                ['driver' => 'pdo_mysql', 'host' => getenv('DB_HOST') ?: 'localhost', 'dbname' => getenv('DB_NAME') ?: 'runwayhub'],
                \Doctrine\DBAL\DriverManager::getDriver()
            );
        } catch (\Exception $e) {
            $output->writeln('<error>Keine Datenbankverbindung: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        // Demo Airline erstellen
        if (!$quiet) {
            $output->writeln('<info>✓ Demo Airline wird erstellt...</info>');
        }
        
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

        $em->getRepository(DemoAirline::class)->save($airline);

        if (!$quiet) {
            $output->writeln('<info>  Name: DemoFly</info>');
            $output->writeln('<info>  Code: DMO</info>');
            $output->writeln('<info>  IATA: DM</info>');
            $output->writeln('<info>  ICAO: DMFLY</info>');
            $output->writeln('<info>  Land: Deutschland</info>');
            $output->writeln('<info>  Status: Aktiv</info>');
        }

        // Demo Benutzer erstellen
        if (!$quiet) {
            $output->writeln('<info>✓ Demo Benutzer werden erstellt...</info>');
        }

        // Admin
        $admin = new DemoUser();
        $admin->setUsername('demo_admin');
        $admin->setEmail('admin@demofly.runwayhub.de');
        $admin->setPassword(Hash::make('demo123'));
        $admin->setFullName('Demo Administrator');
        $admin->setRole('admin');
        $admin->setIsActive(true);
        $admin->setAirline($airline);
        $em->getRepository(DemoUser::class)->save($admin);

        // Staff
        $staff = new DemoUser();
        $staff->setUsername('demo_staff');
        $staff->setEmail('staff@demofly.runwayhub.de');
        $staff->setPassword(Hash::make('demo123'));
        $staff->setFullName('Demo Staff Member');
        $staff->setRole('staff');
        $staff->setIsActive(true);
        $staff->setAirline($airline);
        $em->getRepository(DemoUser::class)->save($staff);

        // Pilot
        $pilot = new DemoUser();
        $pilot->setUsername('demo_pilot');
        $pilot->setEmail('pilot@demofly.runwayhub.de');
        $pilot->setPassword(Hash::make('demo123'));
        $pilot->setFullName('Demo Pilot');
        $pilot->setRole('pilot');
        $pilot->setIsActive(true);
        $pilot->setTypeRating(['737', 'A320', 'B737']);
        $pilot->setCallsign('DMF123');
        $pilot->setAirline($airline);
        $em->getRepository(DemoUser::class)->save($pilot);

        // Guest
        $guest = new DemoUser();
        $guest->setUsername('demo_guest');
        $guest->setEmail('guest@demofly.runwayhub.de');
        $guest->setPassword(Hash::make('demo123'));
        $guest->setFullName('Demo Gast');
        $guest->setRole('guest');
        $guest->setIsActive(true);
        $guest->setAirline($airline);
        $em->getRepository(DemoUser::class)->save($guest);

        if (!$quiet) {
            $output->writeln('<info>  Created: demo_admin (admin@demofly.runwayhub.de)</info>');
            $output->writeln('<info>  Created: demo_staff (staff@demofly.runwayhub.de)</info>');
            $output->writeln('<info>  Created: demo_pilot (pilot@demofly.runwayhub.de)</info>');
            $output->writeln('<info>  Created: demo_guest (guest@demofly.runwayhub.de)</info>');
        }

        // Demo Flotte erstellen
        if (!$quiet) {
            $output->writeln('<info>✓ Demo Flotte wird erstellt...</info>');
        }

        // Boeing 737-800
        $boeing = new DemoAircraft();
        $boeing->setType('Boeing');
        $boeing->setModel('737-800');
        $boeing->setRegistration('DM-FAZ');
        $boeing->setManufacturer('Boeing');
        $boeing->setYear(2023);
        $boeing->setSeats(160);
        $boeing->setEngine('CFM56-7B');
        $boeing->setFuel('Jet A');
        $boeing->setRange(3115);
        $boeing->setMaxAltitude(41000);
        $boeing->setStatus('active');
        $boeing->setAirline($airline);
        $em->getRepository(DemoAircraft::class)->save($boeing);

        // Airbus A320-200
        $airbus = new DemoAircraft();
        $airbus->setType('Airbus');
        $airbus->setModel('A320-200');
        $airbus->setRegistration('DM-FBZ');
        $airbus->setManufacturer('Airbus');
        $airbus->setYear(2022);
        $airbus->setSeats(150);
        $airbus->setEngine('V2500');
        $airbus->setFuel('Jet A');
        $airbus->setRange(3100);
        $airbus->setMaxAltitude(39000);
        $airbus->setStatus('active');
        $airbus->setAirline($airline);
        $em->getRepository(DemoAircraft::class)->save($airbus);

        // Cessna 172
        $cessna = new DemoAircraft();
        $cessna->setType('Cessna');
        $cessna->setModel('172');
        $cessna->setRegistration('DM-C172');
        $cessna->setManufacturer('Cessna');
        $cessna->setYear(2024);
        $cessna->setSeats(4);
        $cessna->setEngine('Lycoming IO-360');
        $cessna->setFuel('AvGas');
        $cessna->setRange(764);
        $cessna->setMaxAltitude(10000);
        $cessna->setStatus('active');
        $cessna->setAirline($airline);
        $em->getRepository(DemoAircraft::class)->save($cessna);

        if (!$quiet) {
            $output->writeln('<info>  Created: DM-FAZ (Boeing 737-800)</info>');
            $output->writeln('<info>  Created: DM-FBZ (Airbus A320-200)</info>');
            $output->writeln('<info>  Created: DM-C172 (Cessna 172)</info>');
        }

        // Demo Flüge erstellen
        if (!$quiet) {
            $output->writeln('<info>✓ Demo Flüge werden erstellt...</info>');
        }

        // Flug DMF001
        $flight = new DemoFlight();
        $flight->setFlightNumber('DMF001');
        $flight->setAircraft($boeing);
        $flight->setPilot($pilot);
        $flight->setAirline($airline);
        $flight->setAirportFrom('MUC');
        $flight->setAirportTo('FRA');
        $flight->setRoute('München-Frankfurt');
        $flight->setDistance(530);
        $flight->setDuration(75);
        $flight->setScheduledDate(new \DateTime('+1 week'));
        $flight->setScheduledTime('08:00:00');
        $flight->setStatus('scheduled');
        $flight->setPriceEconomy(299.00);
        $flight->setPriceBusiness(599.00);
        $flight->setPriceFirst(1299.00);
        $flight->setAvailableSeats(150);
        $flight->setSoldSeats(0);
        $em->getRepository(DemoFlight::class)->save($flight);

        if (!$quiet) {
            $output->writeln('<info>  Created: DMF001 (MUC -> FRA)</info>');
        }

        // Demo PIREP erstellen
        if (!$quiet) {
            $output->writeln('<info>✓ Demo PIREP wird erstellt...</info>');
        }

        $pirep = new DemoPIREP();
        $pirep->setFlightNumber('DMF001');
        $pirep->setAircraft($boeing);
        $pirep->setPilot($pilot);
        $pirep->setAltitude(35000);
        $pirep->setSpeed(450);
        $pirep->setFuel('75%');
        $pirep->setTemperature(-45.0);
        $pirep->setWeatherConditions('Gewitter');
        $pirep->setVisibility('5nm');
        $pirep->setWindSpeed('45kt');
        $pirep->setWindDirection('SW');
        $pirep->setTurbulence('Moderate');
        $pirep->setIcing('None');
        $pirep->setComments('Glatter Flug, schöne Sicht. Gewitterwolken im Süden, aber keine Auswirkungen auf den Flugweg.');
        $pirep->setIsPublic(true);
        $pirep->setStatus('submitted');
        $em->getRepository(DemoPIREP::class)->save($pirep);

        if (!$quiet) {
            $output->writeln('<info>  Created: PIREP für DMF001</info>');
        }

        // Demo Bookings erstellen
        if (!$quiet) {
            $output->writeln('<info>✓ Demo Buchungen werden erstellt...</info>');
        }

        // Buchung 1
        $booking1 = new DemoBooking();
        $booking1->setFlight($flight);
        $booking1->setBookingNumber('DM001');
        $booking1->setPassengerName('Max Mustermann');
        $booking1->setPassengerEmail('max@example.com');
        $booking1->setClass('economy');
        $booking1->setPrice(299.00);
        $booking1->setPaymentMethod('credit_card');
        $booking1->setPaymentStatus('paid');
        $booking1->setStatus('confirmed');
        $em->getRepository(DemoBooking::class)->save($booking1);

        // Buchung 2
        $booking2 = new DemoBooking();
        $booking2->setFlight($flight);
        $booking2->setBookingNumber('DM002');
        $booking2->setPassengerName('Erika Musterfrau');
        $booking2->setPassengerEmail('erika@example.com');
        $booking2->setClass('economy');
        $booking2->setPrice(299.00);
        $booking2->setPaymentMethod('credit_card');
        $booking2->setPaymentStatus('paid');
        $booking2->setStatus('confirmed');
        $em->getRepository(DemoBooking::class)->save($booking2);

        // Buchung 3
        $booking3 = new DemoBooking();
        $booking3->setFlight($flight);
        $booking3->setBookingNumber('DM003');
        $booking3->setPassengerName('Hans Beispielmann');
        $booking3->setPassengerEmail('hans@example.com');
        $booking3->setClass('economy');
        $booking3->setPrice(299.00);
        $booking3->setPaymentMethod('paypal');
        $booking3->setPaymentStatus('paid');
        $booking3->setStatus('confirmed');
        $em->getRepository(DemoBooking::class)->save($booking3);

        if (!$quiet) {
            $output->writeln('<info>  Created: DM001 (Max Mustermann)</info>');
            $output->writeln('<info>  Created: DM002 (Erika Musterfrau)</info>');
            $output->writeln('<info>  Created: DM003 (Hans Beispielmann)</info>');
        }

        // Zusammenfassung
        if (!$quiet) {
            $output->writeln('');
            $output->writeln('<info>=== Installation erfolgreich ===</info>');
            $output->writeln('<info>Demo-Daten wurden installiert:</info>');
            $output->writeln('<info>  - 1 Airline (DemoFly)</info>');
            $output->writeln('<info>  - 4 Benutzer (Admin, Staff, Pilot, Guest)</info>');
            $output->writeln('<info>  - 3 Flugzeuge in der Flotte</info>');
            $output->writeln('<info>  - 1 Demo Flug (DMF001)</info>');
            $output->writeln('<info>  - 1 PIREP</info>');
            $output->writeln('<info>  - 3 Buchungen</info>');
            $output->writeln('');
            $output->writeln('<comment>API Endpoints:</comment>');
            $output->writeln('<info>  GET /api/demo/airline     - Demo Airline Info</info>');
            $output->writeln('<info>  GET /api/demo/users      - Demo User List</info>');
            $output->writeln('<info>  GET /api/demo/aircraft    - Demo Flotte</info>');
            $output->writeln('<info>  GET /api/demo/flights     - Demo Flüge</info>');
            $output->writeln('<info>  GET /api/demo/reset       - Reset alle Demo-Daten</info>');
            $output->writeln('');
            $output->writeln('<comment>Login-Daten:</comment>');
            $output->writeln('<info>  Admin:    demo_admin / demo123</info>');
            $output->writeln('<info>  Staff:    demo_staff / demo123</info>');
            $output->writeln('<info>  Pilot:    demo_pilot / demo123</info>');
            $output->writeln('<info>  Guest:    demo_guest / demo123</info>');
        }

        return Command::SUCCESS;
    }
}
