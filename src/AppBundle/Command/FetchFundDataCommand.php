<?php

namespace AppBundle\Command;

use AppBundle\Service\Fetcher\FundDataFetcher;
use Symfony\Component\Console\Input\InputArgument;

class FetchFundDataCommand extends AbstractAsyncCommand
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
     * @return string
     */
    protected function getCommandName()
    {
        return 'app:fund:fetch';
    }

    /**
     * Method used to process singe element
     * @param mixed $argument . Usually element id to process
     * @return int status code
     */
    protected function process($argument)
    {
        $fundExternalId = (int) $argument;

        var_dump($fundExternalId);die();
    }

    /**
     * Returns elements ids To process
     * @return array
     */
    protected function getElementsIdsToProcess()
    {
        return [1,2,];
    }
}
