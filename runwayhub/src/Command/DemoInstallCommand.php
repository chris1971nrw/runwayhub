<?php

namespace App\Command;

use App\Repository\DemoRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'demo:install',
    description: 'Installiert Demo Airline & User mit allen Demo-Daten',
)]
class DemoInstallCommand extends Command
{
    private DemoRepository $demoRepo;

    public function __construct(DemoRepository $demoRepo)
    {
        parent::__construct();
        $this->demoRepo = $demoRepo;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>=== Demo Data Installation ===</info>');
        $output->writeln('');
        $output->writeln('<comment>Dieses Skript installiert Demo-Daten für RunwayHub.</comment>');
        $output->writeln('<comment>Alle Installationen werden dokumentiert in: docs/changelog.md</comment>');
        $output->writeln('');

        try {
            $this->demoRepo->install();

            $output->writeln('');
            $output->writeln('<info>=== Installation erfolgreich ===</info>');
            $output->writeln('');
            $output->writeln('<comment>Login-Daten:</comment>');
            $output->writeln('<info>  Admin:    demo_admin / demo123</info>');
            $output->writeln('<info>  Staff:    demo_staff / demo123</info>');
            $output->writeln('<info>  Pilot:    demo_pilot / demo123</info>');
            $output->writeln('<info>  Guest:    demo_guest / demo123</info>');
            $output->writeln('');
            $output->writeln('<comment>API Endpoints:</comment>');
            $output->writeln('<info>  GET /api/demo/airline     - Demo Airline Info</info>');
            $output->writeln('<info>  GET /api/demo/users       - Demo User List</info>');
            $output->writeln('<info>  GET /api/demo/aircraft    - Demo Flotte</info>');
            $output->writeln('<info>  GET /api/demo/flights     - Demo Flüge</info>');
            $output->writeln('<info>  GET /api/demo/pireps      - Demo PIREPs</info>');
            $output->writeln('<info>  GET /api/demo/bookings    - Demo Buchungen</info>');
            $output->writeln('<info>  POST /api/demo/reset      - Reset alle Demo-Daten</info>');
            $output->writeln('');
            $output->writeln('<comment>Demo Airline Info:</comment>');
            $output->writeln('<info>  Name: DemoFly</info>');
            $output->writeln('<info>  Code: DMO</info>');
            $output->writeln('<info>  IATA: DM</info>');
            $output->writeln('<info>  ICAO: DMFLY</info>');
            $output->writeln('<info>  Land: Deutschland</info>');
            $output->writeln('<info>  Farbe: #0066cc</info>');
            $output->writeln('<info>  Status: Aktiv</info>');
            $output->writeln('');
            $output->writeln('<comment>Demo Flotte:</comment>');
            $output->writeln('<info>  DM-FAZ (Boeing 737-800)</info>');
            $output->writeln('<info>  DM-FBZ (Airbus A320-200)</info>');
            $output->writeln('<info>  DM-C172 (Cessna 172)</info>');
            $output->writeln('');
            $output->writeln('<comment>Demo Flug:</comment>');
            $output->writeln('<info>  DMF001 (München MUC -> Frankfurt FRA)</info>');
            $output->writeln('');
            $output->writeln('<comment>Demo PIREP:</comment>');
            $output->writeln('<info>  Flug: DMF001</info>');
            $output->writeln('<info>  Wetter: Gewitter</info>');
            $output->writeln('<info>  Höhe: 35000ft</info>');
            $output->writeln('<info>  Geschwindigkeit: 450kt</info>');
            $output->writeln('<info>  Kommentare: Glatter Flug, schöne Sicht</info>');
            $output->writeln('');
            $output->writeln('<comment>Demo Buchungen:</comment>');
            $output->writeln('<info>  DM001 (Max Mustermann)</info>');
            $output->writeln('<info>  DM002 (Erika Musterfrau)</info>');
            $output->writeln('<info>  DM003 (Hans Beispielmann)</info>');
            $output->writeln('');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Installation fehlgeschlagen: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
