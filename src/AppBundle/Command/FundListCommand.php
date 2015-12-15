<?php

namespace AppBundle\Command;

use AppBundle\Entity\Fund;
use AppBundle\Repository\FundRepository;
use AppBundle\Service\ScoreCalculator\OneMonthFundScoreCalculator;
use AppBundle\Service\ScoreCalculator\OneYearFundScoreCalculator;
use AppBundle\Service\ScoreCalculator\ScoreCalculatorAggregate;
use AppBundle\Service\ScoreCalculator\SixMonthFundScoreCalculator;
use AppBundle\Service\ScoreCalculator\ThreeMonthFundScoreCalculator;
use AppBundle\Service\Sorter\Sorter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FundListCommand extends Command
{
    /** @var FundRepository  */
    protected $fundRepository;

    /** @var ScoreCalculatorAggregate */
    protected $scoreCalculatorAggregate;

    /** @var Sorter */
    protected $sorter;

    /**
     * @param FundRepository           $fundRepository
     * @param ScoreCalculatorAggregate $scoreCalculatorAggregate
     * @param Sorter                   $sorter
     */
    public function __construct(
        FundRepository $fundRepository,
        ScoreCalculatorAggregate $scoreCalculatorAggregate,
        Sorter $sorter
    ) {
        $this->fundRepository = $fundRepository;
        $this->scoreCalculatorAggregate = $scoreCalculatorAggregate;
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
        $this->scoreCalculatorAggregate->calculateScore($funds);
        $funds = $this->sorter->getSortedByScore($funds, OneMonthFundScoreCalculator::NAME);

        $io->table(['Fund name', 'External ID', '1M Score', '3M Score', '6M Score', '1Y Score'], array_map(function (Fund $fund) {
            return [
                $fund->getName(),
                $fund->getExternalId(),
                $this->formatScore($fund->getScoreValue(OneMonthFundScoreCalculator::NAME)),
                $this->formatScore($fund->getScoreValue(ThreeMonthFundScoreCalculator::NAME)),
                $this->formatScore($fund->getScoreValue(SixMonthFundScoreCalculator::NAME)),
                $this->formatScore($fund->getScoreValue(OneYearFundScoreCalculator::NAME)),
            ];
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
