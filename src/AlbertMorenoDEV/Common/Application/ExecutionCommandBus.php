<?php
namespace AMD\Common\Application;

class ExecutionCommandBus implements CommandBus
{
    public function handle(Command $command)
    {
        $handleClass = get_class($command).'Handler';
        /** @var CommandHandler $handler */
        $handler = new $handleClass();
        $handler->handle($command);
    }
}