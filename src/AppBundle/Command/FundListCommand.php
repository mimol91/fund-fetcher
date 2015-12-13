<?php

namespace AppBundle\Command;

use AppBundle\Entity\Fund;
use AppBundle\Repository\FundRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FundListCommand extends Command
{
    /** @var FundRepository  */
    protected $fundRepository;

    /**
     * @param FundRepository $fundRepository
     */
    public function __construct(FundRepository $fundRepository)
    {
        $this->fundRepository = $fundRepository;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:found:list')
            ->setDescription('Display fund list');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->table(['Fund name', 'External ID'], array_map(function (Fund $fund) {
            return [$fund->getName(), $fund->getExternalId()];
        }, $this->fundRepository->findAll()));
    }
}
