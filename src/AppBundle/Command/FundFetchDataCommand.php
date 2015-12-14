<?php

namespace AppBundle\Command;

use AppBundle\Entity\Fund;
use AppBundle\Repository\FundRepository;
use AppBundle\Service\Fetcher\FundDataFetcher;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputArgument;

class FundFetchDataCommand extends AbstractAsyncCommand
{
    /** @var FundDataFetcher */
    protected $fundDataFetcher;

    /** @var FundRepository  */
    protected $fundRepository;

    /** @var EntityManager */
    protected $entityManager;

    /**
     * @param string          $kernelRootDir
     * @param FundDataFetcher $fundDataFetcher
     * @param FundRepository  $fundRepository
     * @param EntityManager   $entityManager
     */
    public function __construct(
        $kernelRootDir,
        FundDataFetcher $fundDataFetcher,
        FundRepository $fundRepository,
        EntityManager $entityManager
    ) {
        $this->fundDataFetcher = $fundDataFetcher;
        $this->fundRepository = $fundRepository;
        $this->entityManager = $entityManager;

        parent::__construct($kernelRootDir);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName($this->getCommandName())
            ->setDescription('Fetch fund data')
            ->addArgument(static::ARGUMENT_NAME, InputArgument::OPTIONAL, 'Element id.');
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandName()
    {
        return 'app:fund:fetch';
    }

    /**
     * {@inheritdoc}
     */
    protected function process($argument)
    {
        $fundExternalId = (int) $argument;
        $fund = $this->fundRepository->findOneBy(['externalId' => $fundExternalId]);
        if (!$fund instanceof Fund) {
            throw new \Exception(sprintf('Fund with external id %s not found', $fundExternalId));
        }

        $fundData = $this->fundDataFetcher->fetchData($fund);
        $fund->setFundData($fundData);

        $this->entityManager->flush($fund);
    }

    /**
     * {@inheritdoc}
     */
    protected function getElementsIdsToProcess()
    {
        return $this->fundRepository->getExternalFundIds();
    }
}
