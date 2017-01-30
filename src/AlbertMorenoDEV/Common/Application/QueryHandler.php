<?php
namespace AMD\Common\Application;

interface QueryHandler
{
    public function handle(Query $query);
}