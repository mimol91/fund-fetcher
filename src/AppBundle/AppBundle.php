<?php

namespace AppBundle;

use AppBundle\Compiler\RepositoryCompilerPass;
use AppBundle\Compiler\ScoreCalculatorsCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ScoreCalculatorsCompilerPass());
        $container->addCompilerPass(new RepositoryCompilerPass(), PassConfig::TYPE_BEFORE_REMOVING);
    }
}
