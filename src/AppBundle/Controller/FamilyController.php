<?php
namespace AppBundle\Controller;

use AMD\Catalog\Application\Family\AddFamilyRequest;
use AMD\Catalog\Application\AddFamilyService;
use AMD\Catalog\Application\FamilyResponse;
use AMD\Catalog\Application\FamilyResponseCollection;
use AMD\Catalog\Application\Family\FindAllFamiliesQuery;
use AMD\Catalog\Application\FindFamilyByFamilyIdQuery;
use AMD\Catalog\Application\RemoveFamilyRequest;
use AMD\Catalog\Application\RemoveFamilyService;
use AMD\Catalog\Application\UpdateFamilyRequest;
use AMD\Catalog\Application\UpdateFamilyService;
use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Family\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;
use AMD\Catalog\Domain\Model\Family\InvalidFamilyDataException;
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
     * @throws \LogicException
     */
    public function cgetAction()
    {
        /** @var FamilyRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Family::class);
        $query = new FindAllFamiliesQuery($repository);

        return $this->json(FamilyResponseCollection::createFromFamilyArray($query->execute())->getItems());
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
     * @param int $familyId The family id
     *
     * @return JsonResponse
     * @throws \LogicException
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function getAction($familyId)
    {
        /** @var FamilyRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Family::class);

        /** @var FamilyId $familyId */
        $familyId = FamilyId::create($familyId);

        $query = new FindFamilyByFamilyIdQuery($repository, $familyId);
        try {
            $family = $query->execute();
        } catch (FamilyNotFoundException $e) {
            throw $this->createNotFoundException('No family found for id '.$familyId);
        }

        return $this->json(FamilyResponse::createFromFamily($family));
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
        $entity_manager = $this->getDoctrine()->getManager();
        $repository = $entity_manager->getRepository(Family::class);

        $createFamilyService = new AddFamilyService($repository);

        try {
            $createFamilyService->execute(new AddFamilyRequest(
                $request->get('family_id'),
                $request->get('name'))
            );
            return $this->json([], Response::HTTP_CREATED);
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
            $updateFamilyService->execute(new UpdateFamilyRequest($familyId, $request->get('name')));
            return $this->json([], Response::HTTP_OK);
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
            $removeFamilyService->execute(new RemoveFamilyRequest($familyId));
            return $this->json([], Response::HTTP_OK);
        } catch (FamilyNotFoundException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
