<?php
namespace AppBundle\Controller;

use AMD\Catalog\Application\Product\AddProductCommand;
use AMD\Catalog\Application\Product\FindAllProductsQuery;
use AMD\Catalog\Application\Product\FindProductByIdQuery;
use AMD\Catalog\Application\Product\ProductResponse;
use AMD\Catalog\Application\Product\RemoveProductCommand;
use AMD\Catalog\Application\Product\UpdateProductCommand;
use AMD\Catalog\Domain\Model\Family\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\Product\InvalidProductDataException;
use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Catalog\Domain\Model\Product\ProductNotFoundException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ProductController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "List all products.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     * @throws \LogicException
     */
    public function cgetAction()
    {
        $response = $this->get('amd.query_bus')->handle(new FindAllProductsQuery());

        return $this->json($response->getItems());
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Product for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the product is not found"
     *   }
     * )
     *
     * @param int     $productId      The product id
     *
     * @return JsonResponse
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function getAction($productId)
    {
        /** @var ProductId $productId */
        $productId = ProductId::create($productId);

        try {
            $product = $this->get('amd.query_bus')->handle(new FindProductByIdQuery($productId));
        } catch (ProductNotFoundException $e) {
            throw $this->createNotFoundException('No product found for id '.$productId);
        }

        return $this->json(ProductResponse::createFromProduct($product));
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Add a new Product",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function postAction(Request $request)
    {
        try {
            $this->get('command_bus')->handle(new AddProductCommand(
                $request->get('product_id'),
                $request->get('description'),
                $request->get('family_id'))
            );
            return $this->json([], Response::HTTP_CREATED);
        } catch (FamilyNotFoundException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (InvalidProductDataException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Edit a Product for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the product is not found"
     *   }
     * )
     *
     * @param Request $request
     * @param int $productId The product id
     *
     * @return JsonResponse
     *
     * @throws \LogicException
     * @throws NotFoundHttpException when product or family not exist
     */
    public function putAction(Request $request, $productId)
    {
        try {
            $this->get('command_bus')->handle(new UpdateProductCommand(
                $productId,
                $request->get('description'),
                $request->get('family_id')
            ));
            return $this->json([], Response::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            // ToDo: Maybe a app controller shouldn't know about domain exception directly?
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (FamilyNotFoundException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (InvalidProductDataException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete a Product for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the product is not found"
     *   }
     * )
     *
     * @param int $productId The product id
     *
     * @return JsonResponse
     *
     * @throws \LogicException
     * @throws NotFoundHttpException when page not exist
     */
    public function deleteAction($productId)
    {
        try {
            $this->get('command_bus')->handle(new RemoveProductCommand($productId));
            return $this->json([], Response::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
