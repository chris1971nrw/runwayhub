<?php

namespace RunwayHub\Artisan\Commands;

use RunwayHub\Core\OpenAIP\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * OpenAIP Sync Command
 *
 * Synchronisiert alle Daten aus OpenAIP API
 */
class OpenAIPSyncCommand extends Command
{
    protected static $defaultName = 'openaip:sync';
    protected static $defaultDescription = 'Synchronisiere Daten aus OpenAIP API';

    protected function configure(): void
    {
        $this
            ->addOption('force', 'f', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Forciere Synchronisation')
            ->addOption('limit', 'l', \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Limit für Anzahl', 1000)
            ->addOption('airports', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Nur Flughäfen synchronisieren')
            ->addOption('waypoints', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Nur Wegpunkte synchronisieren')
            ->addOption('airways', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Nur Luftwege synchronisieren')
            ->addOption('navaids', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Nur Navigationshilfen synchronisieren');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->info('OpenAIP Sync gestartet...');

        // Client initialisieren
        $client = new Client();

        // Optionale Datenfilter
        $syncAirports = $input->getOption('airports');
        $syncWaypoints = $input->getOption('waypoints');
        $syncAirways = $input->getOption('airways');
        $syncNavaids = $input->getOption('navaids');
        $force = $input->getOption('force');

        // Limit
        $limit = $input->getOption('limit');

        try {
            // Status
            $status = $client->getSyncStatus();
            $output->writeln('<info>Status:</info>');
            $output->writeln('  Letzte Synchronisation: ' . date('Y-m-d H:i:s', $status['last_sync'] ?: 0));
            $output->writeln('  Letzte Synchronisation erfolgreich: ' . ($status['last_sync_success'] ? 'Ja' : 'Nein'));

            // Wenn nicht erzwungen und Sync aktuell, dann aus
            if (!$force && !$status['last_sync_success']) {
                $output->writeln('<warning>Keine frische Synchronisation gefunden. Führe mit --force aus oder lösche den Cache.</warning>');
                return 0;
            }

            // Synchronisieren
            $result = [];

            if ($syncAirports || !$input->getOption('airports')) {
                $output->writeln('');
                $output->writeln('<info>[Flughäfen] Starte Synchronisation...</info>');
                $result['airports'] = $client->getAirports('', (int)$limit);
                $output->writeln('<info>[Flughäfen] ' . count($result['airports'] ?? []) . ' Flughäfen synchronisiert.</info>');
            }

            if ($syncWaypoints || !$input->getOption('waypoints')) {
                $output->writeln('');
                $output->writeln('<info>[Wegpunkte] Starte Synchronisation...</info>');
                $result['waypoints'] = $client->getWaypoints('', (int)$limit);
                $output->writeln('<info>[Wegpunkte] ' . count($result['waypoints'] ?? []) . ' Wegpunkte synchronisiert.</info>');
            }

            if ($syncAirways || !$input->getOption('airways')) {
                $output->writeln('');
                $output->writeln('<info>[Luftwege] Starte Synchronisation...</info>');
                $result['airways'] = $client->getAirways('', (int)$limit);
                $output->writeln('<info>[Luftwege] ' . count($result['airways'] ?? []) . ' Luftwege synchronisiert.</info>');
            }

            if ($syncNavaids || !$input->getOption('navaids')) {
                $output->writeln('');
                $output->writeln('<info>[Navigationshilfen] Starte Synchronisation...</info>');
                $result['navaids'] = $client->getNavaids('', (int)$limit);
                $output->writeln('<info>[Navigationshilfen] ' . count($result['navaids'] ?? []) . ' Navigationshilfen synchronisiert.</info>');
            }

            // Gesamtergebnis
            $total = count($result['airports'] ?? 0) + count($result['waypoints'] ?? 0) + 
                     count($result['airways'] ?? 0) + count($result['navaids'] ?? 0);
            
            $output->writeln('');
            $output->writeln('<info>========== Zusammenfassung ==========</info>');
            $output->writeln('  Flughäfen: ' . count($result['airports'] ?? 0));
            $output->writeln('  Wegpunkte: ' . count($result['waypoints'] ?? 0));
            $output->writeln('  Luftwege: ' . count($result['airways'] ?? 0));
            $output->writeln('  Navigationshilfen: ' . count($result['navaids'] ?? 0));
            $output->writeln('  Gesamt: ' . $total);

            // Erfolgsflag setzen
            $client->getSyncStatus()['last_sync_success'] = true;
            $client->getSyncStatus()['last_sync_time'] = time();

            return 0;
        } catch (\Exception $e) {
            $output->error('Fehler: ' . $e->getMessage());
            return 1;
        }
    }
}
