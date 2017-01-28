<?php
namespace AMD\Catalog\Infrastructure\Persistence\Doctrine;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\FamilyRepository;
use Doctrine\ORM\EntityRepository;

class DoctrineFamilyRepository extends EntityRepository implements FamilyRepository
{
    public function nextIdentity(): FamilyId
    {
        return FamilyId::create();
    }

    public function add(Family $family)
    {
        $this->_em->persist($family);
        $this->_em->flush(); // ToDo: Must be moved to Application layer, but where exactly?
    }

    public function update(Family $family)
    {
        $this->_em->flush(); // ToDo: Must be moved to Application layer, but where exactly?
    }

    public function remove(Family $family)
    {
        $this->_em->remove($family);
        $this->_em->flush(); // ToDo: Must be moved to Application layer, but where exactly?
    }

    public function findByFamilyId(FamilyId $familyId)
    {
        return $this->find($familyId);
    }
}