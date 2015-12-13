<?php

namespace AppBundle\Command;

use AppBundle\Entity\Fund;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FundCreateCommand extends Command
{
    /** @var ObjectManager  */
    protected $entityManager;

    /**
     * @param ObjectManager $entityManager
     */
    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:fund:generate')
            ->setDescription('Generate a fund entity');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask('Fund name');
        $externalId = $io->ask('External id', null, function ($value) {
            $value = (int) $value;
            if ($value < 1) {
                throw new \RuntimeException('External ID has to be positive integer');
            }

            return $value;
        });

        $fund = $this->createFund($name, $externalId);

        $this->entityManager->persist($fund);
        $this->entityManager->flush();

        $io->success('Fund created');
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
}
