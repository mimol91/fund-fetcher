<?php

namespace AppBundle\Compiler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RepositoryCompilerPass implements CompilerPassInterface
{
    const ENTITY_MANAGER_SERVICE_ID = 'doctrine.orm.default_entity_manager';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        /** @var EntityManager $em */
        $em = $container->get(self::ENTITY_MANAGER_SERVICE_ID);
        $entities = $em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();

        foreach ($entities as $entity) {
            $this->registerRepository($entity, $em, $container);
        }
    }

    /**
     * @param string           $entityClassName
     * @param EntityManager    $em
     * @param ContainerBuilder $container
     */
    private function registerRepository($entityClassName, $em, ContainerBuilder $container)
    {
        $metadata = $em->getClassMetadata($entityClassName);
        $repositoryClassName = $metadata->customRepositoryClassName ?: 'Doctrine\ORM\EntityRepository';

        $container->setDefinition(
            $this->createId($repositoryClassName),
            $this->createDefinition($repositoryClassName, $entityClassName)
        );
    }

    /**
     * @param string $repositoryClassName
     *
     * @return string
     */
    private function createId($repositoryClassName)
    {
        $parts = explode('\\', $repositoryClassName);
        $className = end($parts);

        return substr('repository.'.Container::underscore($className), 0, -11);
    }

    /**
     * @param string $repository
     * @param string $entity
     *
     * @return Definition
     */
    private function createDefinition($repository, $entity)
    {
        return (new Definition($repository))
            ->setFactory([new Reference(self::ENTITY_MANAGER_SERVICE_ID), 'getRepository'])
            ->addArgument($entity);
    }
}
