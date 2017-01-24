<?php
namespace Tests\AppBundle\Controller;

use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
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
     * @group family
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
        $this->assertTrue(isset($decoded[0]['familyId']));
        $this->assertEquals($family->getFamilyId()->getValue(), $decoded[0]['familyId']);
        $this->assertEquals($family->getName(), $decoded[0]['name']);
    }

    /**
     * @test
     * @group family
     */
    public function getFamilyAction()
    {
        /** @var \AMD\Catalog\Domain\Model\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $route = $this->getUrl('api_get_family', ['familyId' => (string)$family->getFamilyId()->getValue(), '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['familyId']));
        $this->assertEquals((string)$family->getFamilyId()->getValue(), $decoded['familyId']);
        $this->assertEquals($family->getName(), $decoded['name']);
    }

    /**
     * @test
     * @group family
     */
    public function getFamilyActionNotFound()
    {
        $route = $this->getUrl('api_get_family', ['familyId' => FamilyId::create()->getValue(), '_format' => 'json']);

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
            json_encode(['family_id' => FamilyId::create()->getValue(), 'name' => 'Family B'])
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
            json_encode(['family_id' => FamilyId::create()->getValue(), 'name' => ''])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST, false);
    }

    /**
     * @test
     * @group family
     */
    public function putFamilyActionShouldModify()
    {
        /** @var \AMD\Catalog\Domain\Model\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $route = $this->getUrl('api_put_family', ['familyId' => (string)$family->getFamilyId()->getValue(), '_format' => 'json']);
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
     * @group family
     */
    public function putFamilyActionBadParameters()
    {
        /** @var \AMD\Catalog\Domain\Model\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $route = $this->getUrl('api_put_family', ['familyId' => $family->getFamilyId()->getValue(), '_format' => 'json']);
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
     * @group family
     */
    public function putFamilyActionNotExists()
    {
        $route = $this->getUrl('api_put_family', ['familyId' => FamilyId::create()->getValue(), '_format' => 'json']);
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
     * @group family
     */
    public function deleteFamilyAction()
    {
        /** @var \AMD\Catalog\Domain\Model\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $route = $this->getUrl('api_get_family', ['familyId' => $family->getFamilyId()->getValue(), '_format' => 'json']);

        $this->client->request('DELETE', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode(), $response->getContent());
    }

    /**
     * @test
     * @group family
     */
    public function deleteFamilyActionNotFound()
    {
        $route = $this->getUrl('api_get_family', ['familyId' => FamilyId::create()->getValue(), '_format' => 'json']);

        $this->client->request('DELETE', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode(), $response->getContent());
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