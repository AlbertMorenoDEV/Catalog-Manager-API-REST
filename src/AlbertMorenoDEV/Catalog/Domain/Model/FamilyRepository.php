<?php
namespace AMD\Catalog\Domain\Model;

use AMD\Catalog\Domain\Model\Family\FamilyId;

interface FamilyRepository
{
    /**
     * @return FamilyId
     */
    public function nextIdentity(): FamilyId;

    /**
     * @param Family $family
     * @return void
     */
    public function add(Family $family);

    /**
     * @param Family $family
     * @return void
     */
    public function update(Family $family);

    /**
     * @param Family $family
     * @return void
     */
    public function remove(Family $family);

    /**
     * @return array
     */
    public function findAll();

    /**
     * @param FamilyId $familyId
     * @return Family|null
     */
    public function findByFamilyId(FamilyId $familyId);
}