<?php
namespace AMD\Catalog\Application\Product;

use AMD\Common\Application\Command;

class RemoveProductCommand implements Command
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}