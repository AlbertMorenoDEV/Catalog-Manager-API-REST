<?php
namespace AppBundle\Controller;

use AMD\Catalog\Application\AddFamilyRequest;
use AMD\Catalog\Application\AddFamilyService;
use AMD\Catalog\Application\RemoveFamilyRequest;
use AMD\Catalog\Application\RemoveFamilyService;
use AMD\Catalog\Application\UpdateFamilyRequest;
use AMD\Catalog\Application\UpdateFamilyService;
use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\InvalidFamilyDataException;
use AMD\Catalog\Infrastructure\Persistence\Doctrine\DoctrineFamilyRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FamilyController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "List all families.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     */
    public function cgetAction()
    {
        $repository = $this->getDoctrine()->getRepository(Family::class);

        $families = $repository->findAll();

        return $this->json($families);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Add a new Family",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \LogicException
     */
    public function postAction(Request $request)
    {
        // $createFamilyService = $this->get('catalog.add_family_service');
        $entity_manager = $this->getDoctrine()->getManager();
        $repository = $entity_manager->getRepository(Family::class);

        $createFamilyService = new AddFamilyService($repository);

        try {
            $response = $createFamilyService->execute(new AddFamilyRequest($request->get('name')));
            return $this->json($response, Response::HTTP_CREATED);
        } catch (InvalidFamilyDataException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Edit a Family for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the family is not found"
     *   }
     * )
     *
     * @param Request $request
     * @param int $familyId The family id
     *
     * @return JsonResponse
     *
     * @throws \LogicException
     * @throws NotFoundHttpException when page not exist
     */
    public function putAction(Request $request, $familyId)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $repository = $entity_manager->getRepository(Family::class);

        $updateFamilyService = new UpdateFamilyService($repository);

        try {
            $response = $updateFamilyService->execute(new UpdateFamilyRequest($familyId, $request->get('name')));
            return $this->json($response, Response::HTTP_OK);
        } catch (FamilyNotFoundException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (InvalidFamilyDataException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete a Family for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the family is not found"
     *   }
     * )
     *
     * @param int     $familyId      The family id
     *
     * @return JsonResponse
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function deleteAction($familyId)
    {
        $entity_manager = $this->getDoctrine()->getManager();
        $repository = $entity_manager->getRepository(Family::class);

        $removeFamilyService = new RemoveFamilyService($repository);

        try {
            $response = $removeFamilyService->execute(new RemoveFamilyRequest($familyId));
            return $this->json($response, Response::HTTP_OK);
        } catch (FamilyNotFoundException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Family for a given id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the family is not found"
     *   }
     * )
     *
     * @param int     $familyId      The family id
     *
     * @return JsonResponse
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function getAction($familyId)
    {
        $family = $this->getDoctrine()
            ->getRepository(Family::class)
            ->find($familyId);

        if (!$family) {
            throw $this->createNotFoundException('No family found for id '.$familyId);
        }

        return $this->json($family);
    }
}
