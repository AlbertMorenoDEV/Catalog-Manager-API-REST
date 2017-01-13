<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Family;
use AppBundle\Entity\Product;
use Tests\AppBundle\Fixtures\Entity\LoadData;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class ProductControllerTest extends WebTestCase
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

    public function testJsonGetProductsAction()
    {
        /** @var Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_get_products', ['_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertCount(2, $decoded);
        $this->assertTrue(isset($decoded[0]['id']));
        $this->assertEquals($product->getDescription(), $decoded[0]['description']);
    }

    public function testJsonGetProductAction()
    {
        /** @var Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_get_product', ['productId' => $product->getId(), '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));
        $this->assertEquals($product->getDescription(), $decoded['description']);
    }

    public function testJsonGetProductActionNotFound()
    {
        $route = $this->getUrl('api_get_product', ['productId' => 9999, '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testJsonPostProductAction()
    {
        $route = $this->getUrl('api_post_product', ['_format' => 'json']);

        /** @var Family $family */
        $family = $this->fixtures->getReference('family-a');

        $this->client->request(
            'POST',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['description' => 'Product C', 'family_id' => $family->getId()])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED, false);
    }

    public function testJsonPostProductActionEmptyDescriptionValidation()
    {
        $route = $this->getUrl('api_post_product', ['_format' => 'json']);

        /** @var Family $family */
        $family = $this->fixtures->getReference('family-a');

        $this->client->request(
            'POST',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['description' => '', 'family_id' => $family->getId()])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST, false);
    }

    public function testJsonPostProductActionFamilyNotFound()
    {
        $route = $this->getUrl('api_post_product', ['_format' => 'json']);

        $this->client->request(
            'POST',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['description' => '', 'family_id' => 9999])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND, false);
    }

    public function testJsonPutProductActionShouldModify()
    {
        /** @var Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_put_product', ['productId' => $product->getId(), '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['description' => 'Product AA', 'family_id' => $product->getFamily()->getId()])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK, false);
    }

    public function testJsonPutProductActionBadParameters()
    {
        /** @var Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_put_product', ['productId' => $product->getId(), '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['description' => '', 'family_id' => $product->getFamily()->getId()])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST, false);
    }

    public function testJsonPutProductActionNotExists()
    {
        $route = $this->getUrl('api_put_product', ['productId' => 9999, '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['description' => 'Product AA', 'family_id' => 9999])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND, false);
    }

    public function testJsonPutProductActionFamilyNotFound()
    {
        /** @var Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_put_product', ['productId' => $product->getId(), '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['description' => 'Product AA', 'family_id' => 9999])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND, false);
    }

    public function testJsonDeleteProductAction()
    {
        /** @var Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_get_product', ['productId' => $product->getId(), '_format' => 'json']);

        $this->client->request('DELETE', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_OK);
    }

    public function testJsonDeleteProductActionNotFound()
    {
        $route = $this->getUrl('api_get_product', ['productId' => 9999, '_format' => 'json']);

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
