<?php
namespace Tests\AppBundle\Controller;

use AMD\Catalog\Domain\Model\Family;
use Tests\AppBundle\Fixtures\Entity\LoadData;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class FamilyControllerTest extends WebTestCase
{
    private $auth;
    /** @var  Client $client */
    private $client;
    /** @var  ReferenceRepository $fixtures */
    private $fixtures;

    public function setUp()
    {
        $this->auth = [
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'password'
        ];

        $this->client = static::createClient([], $this->auth);
        $this->fixtures = $this->loadFixtures([LoadData::class])->getReferenceRepository();
    }

    /**
     * @test
     */
    public function getFamiliesAction()
    {
        /** @var \AMD\Catalog\Domain\Model\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $route = $this->getUrl('api_get_families', ['_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertCount(1, $decoded);
        $this->assertTrue(isset($decoded[0]['id']));
        $this->assertEquals($family->getName(), $decoded[0]['name']);
    }

    /**
     * @test
     */
    public function getFamilyAction()
    {
        /** @var \AMD\Catalog\Domain\Model\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $route = $this->getUrl('api_get_family', ['familyId' => $family->getId(), '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));
        $this->assertEquals($family->getName(), $decoded['name']);
    }

    /**
     * @test
     */
    public function getFamilyActionNotFound()
    {
        $route = $this->getUrl('api_get_family', ['familyId' => 9999, '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     * @group family
     */
    public function postFamilyAction()
    {
        $route = $this->getUrl('api_post_family', ['_format' => 'json']);

        $this->client->request(
            'POST',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Family B'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED, false);
    }

    /**
     * @test
     * @group family
     */
    public function postFamilyActionEmptyNameValidation()
    {
        $route = $this->getUrl('api_post_family', ['_format' => 'json']);

        $this->client->request(
            'POST',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => ''])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST, false);
    }

    /**
     * @test
     */
    public function putFamilyActionShouldModify()
    {
        /** @var \AMD\Catalog\Domain\Model\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $route = $this->getUrl('api_put_family', ['familyId' => $family->getId(), '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Family AA'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK, false);
    }

    /**
     * @test
     */
    public function putFamilyActionBadParameters()
    {
        /** @var \AMD\Catalog\Domain\Model\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $route = $this->getUrl('api_put_family', ['familyId' => $family->getId(), '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => ''])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST, false);
    }

    /**
     * @test
     */
    public function putFamilyActionNotExists()
    {
        $route = $this->getUrl('api_put_family', ['familyId' => 9999, '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Family AA'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND, false);
    }

    /**
     * @test
     */
    public function deleteFamilyAction()
    {
        /** @var \AMD\Catalog\Domain\Model\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $route = $this->getUrl('api_get_family', ['familyId' => $family->getId(), '_format' => 'json']);

        $this->client->request('DELETE', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function deleteFamilyActionNotFound()
    {
        $route = $this->getUrl('api_get_family', ['familyId' => 9999, '_format' => 'json']);

        $this->client->request('DELETE', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    protected function assertJsonResponse(Response $response, $statusCode = 200, $checkValidJson =  true)
    {
        $contentType = 'application/json';
        $this->assertEquals($statusCode, $response->getStatusCode(), $response->getContent());
        $this->assertTrue($response->headers->contains('Content-Type', $contentType), $response->headers);
        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue($decode != null && $decode != false, 'is response valid json: [' . $response->getContent() . ']');
        }
    }
}