<?php

namespace AppBundle\Command;

use AppBundle\Service\Fetcher\FundDataFetcher;
use Symfony\Component\Console\Input\InputArgument;

class FundFetchDataCommand extends AbstractAsyncCommand
{
    /** @var FundDataFetcher */
    protected $fundDataFetcher;

    /**
     * @param $kernelRootDir
     * @param FundDataFetcher $fundDataFetcher
     */
    public function __construct($kernelRootDir, FundDataFetcher $fundDataFetcher)
    {
        $this->fundDataFetcher = $fundDataFetcher;
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
    }

    /**
     * {@inheritdoc}
     */
    protected function getElementsIdsToProcess()
    {
        return [1, 2];
    }
}
