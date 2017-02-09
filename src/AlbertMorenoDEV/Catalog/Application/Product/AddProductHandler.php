<?php
namespace AMD\Catalog\Application\Product;

use AMD\Common\Application\Command;
use AMD\Common\Application\CommandHandler;

class AddProductHandler extends ProductHandler implements CommandHandler
{
    /**
     * @param Command|AddProductCommand $command
     */
    public function handle(Command $command)
    {
        $family = $this->findFamilyOrFail($command->getFamilyId());

        $product = $family->makeProduct($command->getProductId(), $command->getDescription());

        $this->productRepository->add($product);
    }
}