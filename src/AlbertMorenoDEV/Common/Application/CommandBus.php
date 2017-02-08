<?php
namespace AMD\Common\Application;

interface CommandBus
{
    public function handle(Command $command);
}