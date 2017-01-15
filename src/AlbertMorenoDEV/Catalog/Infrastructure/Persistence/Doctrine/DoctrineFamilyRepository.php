<?php
namespace AMD\Catalog\Infrastructure\Persistence\Doctrine;

use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\FamilyRepository;
use Doctrine\ORM\EntityRepository;

class DoctrineFamilyRepository extends EntityRepository implements FamilyRepository
{
    public function add(Family $family)
    {
        $this->_em->persist($family);
        $this->_em->flush(); // ToDo: Must be moved to Application layer, but where exactly?
    }

    public function findById($familyId)
    {
        return $this->find($familyId);
    }
}