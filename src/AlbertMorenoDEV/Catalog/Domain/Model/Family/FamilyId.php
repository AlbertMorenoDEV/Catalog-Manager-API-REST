<?php
namespace AMD\Catalog\Domain\Model\Family;

use Ramsey\Uuid\Uuid;

class FamilyId
{
    private $id;

    private function __construct($id = null)
    {
        $this->id = $id ?: Uuid::uuid4()->toString();
    }

    public static function create($id = null)
    {
        return new static($id);
    }

    public function __toString()
    {
        return $this->id;
    }
}