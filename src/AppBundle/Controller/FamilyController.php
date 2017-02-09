<?php
namespace AppBundle\Controller;

use AMD\Catalog\Application\Family\AddFamilyCommand;
use AMD\Catalog\Application\Family\FamilyResponse;
use AMD\Catalog\Application\Family\FamilyResponseCollection;
use AMD\Catalog\Application\Family\FindAllFamiliesHandler;
use AMD\Catalog\Application\Family\FindAllFamiliesQuery;
use AMD\Catalog\Application\Family\FindFamilyByIdHandler;
use AMD\Catalog\Application\Family\FindFamilyByIdQuery;
use AMD\Catalog\Application\Family\RemoveFamilyCommand;
use AMD\Catalog\Application\Family\UpdateFamilyCommand;
use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;
use AMD\Catalog\Domain\Model\Family\InvalidFamilyDataException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $query = new FindAllFamiliesQuery();
        $handler = new FindAllFamiliesHandler($repository);

        $results = $handler->handle($query);

        return $this->json(FamilyResponseCollection::createFromFamilyArray($results)->getItems());
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

        $query = new FindFamilyByIdQuery($familyId);
        $handler = new FindFamilyByIdHandler($repository);
        try {
            $family = $handler->handle($query);
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
        try {
            $this->get('command_bus')->handle(new AddFamilyCommand(
                $request->get('family_id'),
                $request->get('name')
            ));
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
        try {
            $this->get('command_bus')->handle(new UpdateFamilyCommand($familyId, $request->get('name')));
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
        try {
            $this->get('command_bus')->handle(new RemoveFamilyCommand($familyId));
            return $this->json([], Response::HTTP_OK);
        } catch (FamilyNotFoundException $e) {
            return $this->json(['errors' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
