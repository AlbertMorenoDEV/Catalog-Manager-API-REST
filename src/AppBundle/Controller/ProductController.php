<?php
namespace AppBundle\Controller;

use AMD\Catalog\Application\AddProductRequest;
use AMD\Catalog\Application\AddProductService;
use AMD\Catalog\Application\FindAllProductsQuery;
use AMD\Catalog\Application\FindProductByProductIdQuery;
use AMD\Catalog\Application\ProductResponse;
use AMD\Catalog\Application\ProductResponseCollection;
use AMD\Catalog\Application\RemoveProductRequest;
use AMD\Catalog\Application\RemoveProductService;
use AMD\Catalog\Application\UpdateProductRequest;
use AMD\Catalog\Application\UpdateProductService;
use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\InvalidProductDataException;
use AMD\Catalog\Domain\Model\Product\Product;
use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Catalog\Domain\Model\ProductNotFoundException;
use AMD\Catalog\Domain\Model\ProductRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        /** @var ProductRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Product::class);

        $query = new FindAllProductsQuery($repository);

        return $this->json(ProductResponseCollection::createFromProductArray($query->execute())->getItems());
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
        /** @var ProductRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Product::class);

        /** @var ProductId $productId */
        $productId = ProductId::create($productId);

        $query = new FindProductByProductIdQuery($repository, $productId);
        try {
            $product = $query->execute();
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
        $entity_manager = $this->getDoctrine()->getManager();
        $familyRepository = $entity_manager->getRepository(Family::class);
        $productRepository = $entity_manager->getRepository(Product::class);

        $addProductService = new AddProductService($familyRepository, $productRepository);

        try {
            $addProductService->execute(new AddProductRequest(
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
        $entity_manager = $this->getDoctrine()->getManager();
        $familyRepository = $entity_manager->getRepository(Family::class);
        $productRepository = $entity_manager->getRepository(Product::class);

        $updateFamilyService = new UpdateProductService($familyRepository, $productRepository);

        try {
            $updateFamilyService->execute(new UpdateProductRequest(
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
        $entity_manager = $this->getDoctrine()->getManager();
        $repository = $entity_manager->getRepository(Product::class);

        $removeProductService = new RemoveProductService($repository);

        try {
            $removeProductService->execute(new RemoveProductRequest($productId));
            return $this->json([], Response::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
