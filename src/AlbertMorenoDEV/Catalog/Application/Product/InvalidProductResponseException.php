<?php
namespace AMD\Catalog\Application\Product;

class InvalidProductResponseException extends \Exception
{
    public function __construct($class)
    {
        parent::__construct(sprintf('"%s" is not a valid product response class.', get_class($class)));
    }
}