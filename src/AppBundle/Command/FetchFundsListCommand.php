<?php

namespace AppBundle\Command;

use AppBundle\Entity\Fund;
use Doctrine\Common\Persistence\ObjectManager;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;

class FetchFundsListCommand extends Command
{
    /** @var ObjectManager  */
    protected $entityManager;

    /** @var Client  */
    private $httpClient;

    /** @var string  */
    private $fetchUrl;

    public function __construct(ObjectManager $entityManager, $fetchUrl)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->httpClient = new Client();
        $this->fetchUrl = $fetchUrl;
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

        $response = $this->httpClient->get($this->fetchUrl);
        if (200 !== $response->getStatusCode()) {
            $io->error('Unable to fetch fund list!');

            return;
        }
        $html = $response->getBody()->__toString();
        $funds = $this->parse($html);
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
     * @param string $html
     *
     * @return array
     */
    private function parse($html)
    {
        $crawler = new Crawler($html);
        $crawler = $crawler->filter('select > option');
        $funds = $crawler->extract(array('_text', 'value'));
        $funds = array_filter($funds, function (array $fundData) {
            $fundId = (int) $fundData[1];

            return $fundId > 0;
        });

        return array_map(function (array $fundData) {
            return $this->createFund($fundData[0], (int) $fundData[1]);
        }, $funds);
    }
}
