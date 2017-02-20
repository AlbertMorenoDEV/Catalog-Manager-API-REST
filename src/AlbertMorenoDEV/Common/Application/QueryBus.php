<?php
namespace AMD\Common\Application;

use Symfony\Component\DependencyInjection\ContainerInterface;

class QueryBus
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $handlers;

    /**
     * QueryBus constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->handlers = [];
    }
    /**
     * @param Query $query
     *
     * @return mixed
     */
    public function handle(Query $query)
    {
        return $this->handlers[get_class($query)]->handle($query);
    }

    /**
     * @param string $query
     * @param QueryHandler $handler
     *
     * @return void
     */
    public function addHandler(string $query, QueryHandler $handler)
    {
        $this->handlers[$query] = $handler;
    }
}