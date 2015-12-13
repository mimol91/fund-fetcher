<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

abstract class AbstractAsyncCommand extends Command
{
    const WORKERS_AMOUNT = 4;
    const REFRESH_INTERVAL = 0.5; //in seconds
    const ARGUMENT_NAME = 'elementId';

    /** @var SymfonyStyle */
    protected $io;

    /** @var string  */
    protected $kernelRootDir;

    /**
     * AbstractAsyncCommand constructor.
     *
     * @param string $kernelRootDir
     */
    public function __construct($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;

        parent::__construct();
    }

    /**
     * @return string
     */
    abstract protected function getCommandName();

    /**
     * Method used to process singe element.
     *
     * @param mixed $argument. Usually element id to process
     *
     * @return int status code
     */
    abstract protected function process($argument);

    /**
     * Returns elements ids To process.
     *
     * @return array
     */
    abstract protected function getElementsIdsToProcess();

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument(static::ARGUMENT_NAME)) {
            $this->process($input->getArgument(static::ARGUMENT_NAME));

            return 0;
        }

        $versionIds = $this->getElementsIdsToProcess();
        $this->createJobs($versionIds);

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @param array $elements
     */
    protected function createJobs(array $elements)
    {
        $chunks = array_chunk($elements, static::WORKERS_AMOUNT);

        $this->io->writeln((sprintf('Starting with %d workers', static::WORKERS_AMOUNT)));

        $progressBar = $this->io->createProgressBar(count($elements));
        $progressBar->setFormat('verbose');

        foreach ($chunks as $chunk) {
            $reamingJobs = count($chunk);
            $processHolder = [];

            foreach ($chunk as $versionId) {
                $process = $this->createCommandProcess($versionId);
                $process->start();
                $processHolder[] = $process;
            }

            while ($reamingJobs > 0) {
                /** @var Process $process */
                foreach ($processHolder as $key => $process) {
                    if ($process->isSuccessful()) {
                        $progressBar->advance();
                        --$reamingJobs;
                        unset($processHolder[$key]);
                    }
                    $errorOutput = trim($process->getErrorOutput());
                    if ($errorOutput) {
                        $this->io->error(sprintf('<error>%s</error>', $errorOutput));
                        throw new \RuntimeException('Error occurs. Aborting...');
                    }
                }

                usleep(1e6 * static::REFRESH_INTERVAL);
            }
        }

        $progressBar->finish();
        $this->io->newLine(2);
        $this->io->success('Done');
    }

    /**
     * @param mixed $argument
     *
     * @return Process
     */
    protected function createCommandProcess($argument)
    {
        return (new Process(sprintf(
            '%s/../bin/console %s %s',
            $this->kernelRootDir,
            $this->getCommandName(),
            $argument
        )))->setTimeout(null);
    }
}
