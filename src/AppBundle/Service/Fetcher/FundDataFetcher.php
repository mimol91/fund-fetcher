<?php

namespace AppBundle\Service\Fetcher;

use AppBundle\Doctrine\FundDataCollection;
use AppBundle\Entity\Fund;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class FundDataFetcher
{
    /** @var string  */
    private $fetchUrlPattern;

    /** @var Client  */
    private $httpClient;

    /** @var FundCSVDataParser */
    private $fundDataParser;

    /**
     * @param string            $fetchUrlPattern
     * @param FundCSVDataParser $fundDataParser
     */
    public function __construct($fetchUrlPattern, FundCSVDataParser $fundDataParser)
    {
        $this->fetchUrlPattern = $fetchUrlPattern;
        $this->fundDataParser = $fundDataParser;
        $this->httpClient = new Client();
    }

    /**
     * @param Fund $fund
     *
     * @return FundDataCollection
     *
     * @throws FundFetcherException
     */
    public function fetchData(Fund $fund)
    {
        $downloadUrl = $this->getDownloadUrl($fund);

        /** @var ResponseInterface $response */
        $response = $this->httpClient->get($downloadUrl);
        if (200 !== $response->getStatusCode()) {
            throw new FundFetcherException('Unable to fetch fund data', $response->getStatusCode());
        }

        /* @var StreamInterface $data */
        $stream = $response->getBody();

        return $this->fundDataParser->getFundData($stream->__toString());
    }

    /**
     * @param Fund $fund
     *
     * @return string
     */
    protected function getDownloadUrl(Fund $fund)
    {
        return sprintf($this->fetchUrlPattern, $fund->getExternalId());
    }
}
