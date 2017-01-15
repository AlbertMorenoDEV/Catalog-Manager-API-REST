<?php
namespace AMD\Catalog\Application;

class AddFamilyRequest
{
    /** @var  string */
    private $name;

    /** @var  int */
    private $familyId;

    public function __construct($name, $familyId)
    {
        $this->name = $name;
        $this->familyId = $familyId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getFamilyId()
    {
        return $this->familyId;
    }
}