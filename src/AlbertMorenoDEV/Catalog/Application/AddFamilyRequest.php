<?php
namespace AMD\Catalog\Application;

class AddFamilyRequest
{
    /** @var  string */
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}