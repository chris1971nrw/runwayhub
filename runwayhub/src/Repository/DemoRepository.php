<?php

namespace App\Repository;

use App\Entity\DemoAirline;
use App\Entity\DemoUser;
use App\Entity\DemoAircraft;
use App\Entity\DemoFlight;
use App\Entity\DemoPIREP;
use App\Entity\DemoBooking;
use Doctrine\ORM\EntityManagerInterface;

class DemoRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Erstellt alle Demo-Daten
     */
    public function install(): void
    {
        // Airline
        $airline = new DemoAirline();
        $airline->setName('DemoFly');
        $airline->setCode('DMO');
        $airline->setIata('DM');
        $airline->setIcao('DMFLY');
        $airline->setCallsign('DEMOFLY');
        $airline->setCountry('DE');
        $airline->setColor('#0066cc');
        $airline->setIsActive(true);
        $this->em->persist($airline);
        $this->em->flush();

        // User Repo
        $userRepository = $this->em->getRepository(DemoUser::class);
        $userRepository->up($airline);

        // Aircraft
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

        $this->em->persist($boeing);
        $this->em->persist($airbus);
        $this->em->persist($cessna);
        $this->em->flush();

        // Pilot
        $pilot = $userRepository->findOneBy(['username' => 'demo_pilot']);

        // Flight
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

        $this->em->persist($flight);
        $this->em->flush();

        // PIREP
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

        $this->em->persist($pirep);
        $this->em->flush();

        // Booking Repo
        $bookingRepository = $this->em->getRepository(DemoBooking::class);
        $bookingRepository->up($flight, $pilot);

        echo "Demo Data Installation erfolgreich!\n";
        echo "  - 1 Airline (DemoFly)\n";
        echo "  - 4 Benutzer (Admin, Staff, Pilot, Guest)\n";
        echo "  - 3 Flugzeuge in der Flotte\n";
        echo "  - 1 Demo Flug (DMF001)\n";
        echo "  - 1 PIREP\n";
        echo "  - 3 Buchungen\n";
    }

    /**
     * Löscht alle Demo-Daten und installiert neu
     */
    public function reset(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\DemoAirline')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\DemoUser')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\DemoAircraft')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\DemoFlight')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\DemoPIREP')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\DemoBooking')->execute();

        $this->install();
    }
}
