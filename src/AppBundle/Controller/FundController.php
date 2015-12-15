<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FundController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function listAction()
    {
        $funds = $this->get('repository.fund')->findAll();
        $fundScoreCalculatorAggregate = $this->get('app.score_calculator.aggregate');
        $fundScoreCalculatorAggregate->calculateScore($funds);

        $jsonData = $this->get('app.serializer.fund')->serializeFunds($funds);

        return new Response($jsonData);
    }
}
