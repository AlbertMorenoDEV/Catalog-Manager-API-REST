<?php
namespace AMD\Catalog\Application;

class UpdateProductRequest
{
    /** @var  string */
    private $id;

    /** @var  string */
    private $description;

    /** @var  string */
    private $familyId;

    public function __construct($id, $description, $familyId)
    {
        $this->id = $id;
        $this->description = $description;
        $this->familyId = $familyId;
    }

    /**
     * @return string
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
     * @return string
     */
    public function getFamilyId()
    {
        return $this->familyId;
    }
}