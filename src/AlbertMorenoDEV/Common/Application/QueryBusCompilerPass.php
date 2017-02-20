<?php
namespace AMD\Common\Application;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class QueryBusCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('amd.query_bus')) {
            return;
        }

        $queryBus = $container->findDefinition('amd.query_bus');
        $taggedServices = $container->findTaggedServiceIds('amd.query_handler');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $queryBus->addMethodCall('addHandler', [$attributes['handles'], new Reference($id)]);
            }
        }
    }
}