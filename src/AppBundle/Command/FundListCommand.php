<?php

namespace AppBundle\Command;

use AppBundle\Entity\Fund;
use AppBundle\Repository\FundRepository;
use AppBundle\Service\ScoreCalculator\FundScoreCalculatorInterface;
use AppBundle\Service\Sorter\Sorter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FundListCommand extends Command
{
    /** @var FundRepository  */
    protected $fundRepository;

    /** @var FundScoreCalculatorInterface */
    protected $fundScoreCalculator;

    /** @var Sorter */
    protected $sorter;

    /**
     * @param FundRepository               $fundRepository
     * @param FundScoreCalculatorInterface $fundScoreCalculator
     * @param Sorter                       $sorter
     */
    public function __construct(
        FundRepository $fundRepository,
        FundScoreCalculatorInterface $fundScoreCalculator,
        Sorter $sorter
    ) {
        $this->fundRepository = $fundRepository;
        $this->fundScoreCalculator = $fundScoreCalculator;
        $this->sorter = $sorter;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:fund:list')
            ->setDescription('Display fund list');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $funds = $this->fundRepository->findAll();
        $funds = $this->sorter->getSortedByScore($funds, $this->fundScoreCalculator);

        $io->table(['Fund name', 'External ID', 'Score'], array_map(function (Fund $fund) {
            return [$fund->getName(), $fund->getExternalId(), $this->formatScore($fund->getScore())];
        }, $funds));
    }

    /**
     * @param $score
     *
     * @return string
     */
    private function formatScore($score)
    {
        if (null === $score) {
            return str_pad('---', 4, ' ', STR_PAD_LEFT);
        }

        return sprintf('%+0.2f', $score);
    }
}
