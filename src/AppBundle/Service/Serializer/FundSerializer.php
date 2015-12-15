<?php

namespace AppBundle\Service\Serializer;

use AppBundle\Entity\Fund;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FundSerializer
{
    /** @var Serializer  */
    private $serializer;

    public function __construct()
    {
        $normalizer = new ObjectNormalizer(new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader())));
        $this->serializer = new Serializer([$normalizer], [new JsonEncoder()]);

    }

    /**
     * @param Fund $fund
     * @return string
     */
    public function serializeFund(Fund $fund)
    {

        return $this->serializer->serialize($fund, 'json', ['groups' => ['object']]);
    }

    /**
     * @param Fund[] $funds
     * @return string
     */
    public function serializeFunds(array $funds)
    {
        return $this->serializer->serialize($funds, 'json', ['groups' => ['list']]);
    }
}
