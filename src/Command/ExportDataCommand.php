<?php

namespace App\Command;

use App\Service\ExportData\DataExportInterface;
use App\Service\ExportData\LogFileInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ExportDataCommand extends Command
{
    protected static $defaultName = 'app:export-data';
    protected static $defaultDescription = 'Export specific type of data';
    /**
     * @var DataExportInterface[]
     */
    private $exportData;
    private $directory;
    /**
     * @var LogFileInterface[]
     */
    private $exportLog;

    public function __construct(iterable $exportData, string $directory, iterable $exportLog)
    {
        parent::__construct(null);

        $this->exportData = $exportData;
        $this->directory = $directory;
        $this->exportLog = $exportLog;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // this code juste add progress Bar
        $filesystem = new Filesystem();
        ProgressBar::setFormatDefinition('minimal', 'Progress: %percent%%');
        $progressBar = new ProgressBar($output, 3);
        $progressBar->setFormat('minimal');
        $progressBar->start();
        // start logic Business
        foreach ($this->exportData as $data) {
            $filesystem->dumpFile($this->directory."/{$data->nameFile()}.csv", $data->export());
        }

        foreach ($this->exportLog as $logFile) {
            $logFile->logDateGeneration();
        }
        // end logic Business
        $progressBar->advance();
        $output->writeln('finished');
        $progressBar->finish();

        return Command::SUCCESS;
    }
}
