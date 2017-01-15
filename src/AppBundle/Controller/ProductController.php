<?php
namespace AppBundle\Controller;

use AMD\Catalog\Application\AddProductRequest;
use AMD\Catalog\Application\AddProductService;
use AMD\Catalog\Application\UpdateProductRequest;
use AMD\Catalog\Application\UpdateProductService;
use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\InvalidProductDataException;
use AMD\Catalog\Domain\Model\Product;
use AMD\Catalog\Domain\Model\ProductNotFoundException;
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
     */
    public function cgetAction()
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);

        $products = $repository->findAll();

        return $this->json($products);
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
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($productId);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$productId);
        }

        return $this->json($product);
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
        $repository = $entity_manager->getRepository(Product::class);

        $addProductService = new AddProductService($repository);

        try {
            $response = $addProductService->execute(new AddProductRequest($request->get('description'), $request->get('family_id')));
            return $this->json($response, Response::HTTP_CREATED);
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
        $repository = $entity_manager->getRepository(Product::class);

        $updateFamilyService = new UpdateProductService($repository);

        try {
            $response = $updateFamilyService->execute(new UpdateProductRequest($productId, $request->get('description'), $request->get('family_id')));
            return $this->json($response, Response::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            // ToDo: Maybe a app controller shouldn't know about domain exception directly?
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
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AMD:Product')->find($productId);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$productId);
        }

        $em->remove($product);
        $em->flush();

        return $this->json($product, Response::HTTP_OK);
    }
}