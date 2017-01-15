<?php
namespace AMD\Catalog\Application;

class UpdateProductRequest
{
    /** @var  int */
    private $id;

    /** @var  string */
    private $description;

    /** @var  int */
    private $familyId;

    public function __construct($id, $description, $familyId)
    {
        $this->id = $id;
        $this->description = $description;
        $this->familyId = $familyId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getFamilyId()
    {
        return $this->familyId;
    }
}