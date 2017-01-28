<?php
namespace AMD\Catalog\Application\Product;

class RemoveProductCommand
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