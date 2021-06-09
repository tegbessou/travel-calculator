<?php

namespace App\Command;

use App\Entity\Airport;
use App\Factory\AirportFactory;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AirportImportCommand extends Command
{
    public const BATCH_SIZE = 100;
    protected static $defaultName = 'app:airport:import';
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        parent::__construct(self::$defaultName);
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function configure()
    {
        $this
            ->addOption('filename', 'f', InputOption::VALUE_REQUIRED)
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->handleFile($input->getOption('filename'));
        } catch (\Exception $exception) {
            $this->logger->critical('Airport Import Command: '.$exception->getMessage());

            throw $exception;
        }

        return 0;
    }

    /** Move this in AirportImportHandler */
    private function handleFile(string $filename)
    {
        $filepath = __DIR__.'/../../assets/data/'.$filename;

        if (!file_exists($filepath)) {
            throw new \Exception('File not found: '.$filename);
        }

        if (($fileResource = fopen($filepath, 'r')) === false) {
            throw new \Exception('File can\'t be open: '.$filename);
        }

        $index = 0;
        while ($line = fgetcsv($fileResource, 1000, ',')) {
            if (self::isHeader($line)) {
                continue;
            }

            $airport = $this->handleAirportCreation($line);
            $this->entityManager->persist($airport);

            if ($index % self::BATCH_SIZE === 1) {
                $this->entityManager->flush();
            }
            $index++;
        }
    }

    /** Move this in AirportImportHandler */
    private static function isHeader(array $airportLine): bool
    {
        return $airportLine[1] === 'latitude_deg' && $airportLine[2] === 'longitude_deg';
    }

    /** Move this in AirportImportHandler */
    private function handleAirportCreation(array $airportLine): Airport
    {
        return AirportFactory::initialize(
            $airportLine[0],
            $airportLine[1],
            $airportLine[2],
            $airportLine[3],
            $airportLine[4],
            $airportLine[5],
        );
    }
}