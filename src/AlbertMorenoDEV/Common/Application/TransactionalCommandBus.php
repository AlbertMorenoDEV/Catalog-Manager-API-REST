<?php
namespace AMD\Common\Application;

class TransactionalCommandBus implements CommandBus
{
    /** @var  CommandBus */
    private $innerCommandBus;

    public function __construct(CommandBus $innerCommandBus)
    {
        $this->innerCommandBus = $innerCommandBus;
    }

    public function handle(Command $command)
    {
        try {
            // start transaction
            $this->innerCommandBus->handle($command);
            // commit transaction
        } catch (\Exception $e) {
            // rollback transaction
        }
    }
}