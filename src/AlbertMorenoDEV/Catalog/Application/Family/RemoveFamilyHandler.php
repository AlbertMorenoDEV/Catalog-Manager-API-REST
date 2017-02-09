<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;
use AMD\Common\Application\Command;
use AMD\Common\Application\CommandHandler;

class RemoveFamilyHandler implements CommandHandler
{
    /** @var FamilyRepository */
    private $repository;

    /**
     * RemoveFamilyHandler constructor.
     * @param FamilyRepository $repository
     */
    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Command|RemoveFamilyCommand $command
     * @throws \AMD\Catalog\Domain\Model\Family\FamilyNotFoundException
     */
    public function handle(Command $command)
    {
        /** @var \AMD\Catalog\Domain\Model\Family\Family $family */
        $family = $this->repository->findByFamilyId($command->getFamilyId());

        if (!$family) {
            throw new FamilyNotFoundException('No family found for id '.$command->getFamilyId());
        }

        $this->repository->remove($family);
    }
}