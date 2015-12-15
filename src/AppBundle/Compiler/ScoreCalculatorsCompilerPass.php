<?php

namespace AppBundle\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ScoreCalculatorsCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('app.score_calculator.aggregate')) {
            return;
        }

        $aggregate = $container->findDefinition('app.score_calculator.aggregate');
        $taggedServices = $container->findTaggedServiceIds('app.score_calculator');

        foreach ($taggedServices as $serviceId => $tags) {
            $aggregate->addMethodCall('registerScoreCalculator', [new Reference($serviceId)]);
        }
    }
}
