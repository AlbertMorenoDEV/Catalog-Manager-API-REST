<?php
namespace AMD\Catalog\Application;

class RemoveProductRequest
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