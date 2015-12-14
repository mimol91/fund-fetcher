<?php

namespace AppBundle\Command;

use AppBundle\Entity\Fund;
use AppBundle\Repository\FundRepository;
use AppBundle\Service\ScoreCalculator\FundScoreCalculatorInterface;
use AppBundle\Service\ScoreCalculator\ScoreCalculatorException;
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

    /**
     * @param FundRepository $fundRepository
     * @param FundScoreCalculatorInterface $fundScoreCalculator
     */
    public function __construct(
        FundRepository $fundRepository,
        FundScoreCalculatorInterface $fundScoreCalculator
    ) {
        $this->fundRepository = $fundRepository;
        $this->fundScoreCalculator = $fundScoreCalculator;

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

        $io->table(['Fund name', 'External ID', 'Score'], array_map(function (Fund $fund) {
            try {
                $score = $this->fundScoreCalculator->getScore($fund);
            } catch (ScoreCalculatorException $e) {
                $score = '-';
            }

            return [$fund->getName(), $fund->getExternalId(), $this->formatScore($score)];
        }, $this->fundRepository->findAll()));
    }

    /**
     * @param $score
     *
     * @return string
     */
    private function formatScore($score)
    {
        if (!is_float($score)) {
            return $score;
        }

        return sprintf('%+0.2f', $score);
    }
}
