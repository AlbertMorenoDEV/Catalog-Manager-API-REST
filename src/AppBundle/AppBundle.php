<?php
namespace AppBundle;

use AMD\Common\Application\QueryBusCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new QueryBusCompilerPass());
    }
}
