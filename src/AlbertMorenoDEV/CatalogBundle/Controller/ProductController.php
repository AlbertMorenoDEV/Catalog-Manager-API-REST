<?php
namespace AlbertMorenoDEV\CatalogBundle\Controller;

use AlbertMorenoDEV\CatalogBundle\Entity\Family;
use AlbertMorenoDEV\CatalogBundle\Entity\Product;
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
        $repository = $this->getDoctrine()->getRepository('AlbertMorenoDEVCatalogBundle:Product');

        $products = $repository->findAll();

//        $encoder = new JsonEncoder();
//        $normalizer = new ObjectNormalizer();
//
//        $normalizer->setCircularReferenceHandler(function ($object) {
//            return $object->getName();
//        });
//
//        $serializer = new Serializer([$normalizer], [$encoder]);
//        return $this->json($serializer->serialize($products, 'json'));

        return $this->json($products);
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
        $em = $this->getDoctrine()->getManager();
        /** @var Family $family */
        $family = $em->getRepository('AlbertMorenoDEVCatalogBundle:Family')->find($request->get('family_id'));

        if (!$family) {
            throw $this->createNotFoundException('No family found for id '.$request->get('family_id'));
        }

        $product = new Product();
        $product->setDescription($request->get('description'));
        $product->setFamily($family);

        $validator = $this->get('validator');
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], Response::HTTP_NOT_ACCEPTABLE);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->json(["id" => $product->getId()]);
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
        $em = $this->getDoctrine()->getManager();

        /** @var Product $product */
        $product = $em->getRepository('AlbertMorenoDEVCatalogBundle:Product')->find($productId);
        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$productId);
        }

        /** @var Family $family */
        $family = $em->getRepository('AlbertMorenoDEVCatalogBundle:Family')->find($request->get('family_id'));

        if (!$family) {
            throw $this->createNotFoundException('No family found for id '.$request->get('family_id'));
        }

        $product->setDescription($request->get('description'));
        $product->setFamily($family);

        $validator = $this->get('validator');
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], Response::HTTP_NOT_ACCEPTABLE);
        }

        $em->flush();

        return $this->json($product);
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
        $product = $em->getRepository('AlbertMorenoDEVCatalogBundle:Product')->find($productId);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$productId);
        }

        $em->remove($product);
        $em->flush();

        return $this->json($product);
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
            ->getRepository('AlbertMorenoDEVCatalogBundle:Product')
            ->find($productId);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$productId);
        }

        return $this->json($product);
    }
}
