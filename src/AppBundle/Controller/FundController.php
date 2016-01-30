<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Fund;
use AppBundle\Service\ScoreCalculator\OneMonthFundScoreCalculator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class FundController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $funds = $this->get('repository.fund')->findAll();
        $sorter = $this->get('app.fetcher.sorter');
        $fundScoreCalculatorAggregate = $this->get('app.score_calculator.aggregate');
        $fundScoreCalculatorAggregate->calculateScore($funds);
        $funds = $sorter->getSortedByScore($funds, OneMonthFundScoreCalculator::NAME);

        $jsonData = $this->get('app.serializer.fund')->serializeFunds($funds);

        return new Response($jsonData);
    }

    /**
     * @Route("/{id}", requirements={"id" = "\d+"}, name="details")
     * @Method({"GET"})
     */
    public function detailsAction(Fund $fund)
    {
        $fundScoreCalculatorAggregate = $this->get('app.score_calculator.aggregate');
        $fundScoreCalculatorAggregate->calculateScore([$fund]);

        $jsonData = $this->get('app.serializer.fund')->serializeFund($fund);

        return new Response($jsonData);
    }

    /**
     * @Route("/fetch", name="fetch")
     * @Method({"GET"})
     */
    public function fetchAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(['command' => 'app:fund:fetch']);

        $output = new NullOutput();
        $application->run($input, $output);

        return new Response(null, 204);
    }
}
