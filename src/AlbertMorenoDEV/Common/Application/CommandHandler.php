<?php
namespace AMD\Common\Application;

interface CommandHandler
{
    public function handle(Command $command);
}