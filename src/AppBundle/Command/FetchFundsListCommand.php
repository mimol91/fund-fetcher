<?php

namespace AppBundle\Command;

use AppBundle\Entity\Fund;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FetchFundsListCommand extends Command
{
    /** @var ObjectManager  */
    protected $entityManager;

    /** @var string  */
    private $filePath;

    public function __construct(ObjectManager $entityManager, $kernelDir, $filename)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->filePath = sprintf('%s/Resources/%s', $kernelDir, $filename);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:fund:fetch-list')
            ->setDescription('Fetch and create funds');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        if (!is_readable($this->filePath)) {
            $io->error(sprintf('Unable to read %s!', $this->filePath));

            return;
        }

        $funds = $this->parse(file_get_contents($this->filePath));
        foreach ($funds as $fund) {
            $this->entityManager->persist($fund);
        }
        $this->entityManager->flush();

        $io->success(sprintf('%d funds created', count($funds)));
    }

    /**
     * @param string $name
     * @param int    $externalId
     *
     * @return Fund
     */
    protected function createFund($name, $externalId)
    {
        return (new Fund())
            ->setName($name)
            ->setExternalId($externalId);
    }

    /**
     * @param string $data
     *
     * @return array
     */
    private function parse($data)
    {
        return array_map(function (array $fundData) {
            return $this->createFund($fundData['Name'], (int) $fundData['Id']);
        }, json_decode($data, true));
    }
}
