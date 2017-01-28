<?php
namespace Tests\Functional;

use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Product\Product;
use AMD\Catalog\Domain\Model\Product\ProductId;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;
use Tests\fixtures\LoadData;

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

    /**
     * @test
     * @group product
     */
    public function getProductsAction()
    {
        /** @var \AMD\Catalog\Domain\Model\Product\Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_get_products', ['_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertCount(2, $decoded);
        $this->assertArrayHasKey('productId', $decoded[0]);
        $this->assertArrayHasKey('description', $decoded[0]);
        $productId = $product->getProductId()->getValue();
        $description = $product->getDescription();
        $this->assertTrue($productId === $decoded[0]['productId'] || $productId === $decoded[1]['productId']);
        $this->assertTrue($description === $decoded[0]['description'] || $description === $decoded[1]['description']);
    }

    /**
     * @test
     * @group product
     */
    public function getProductAction()
    {
        /** @var \AMD\Catalog\Domain\Model\Product\Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_get_product', ['productId' => $product->getProductId()->getValue(), '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['productId']));
        $this->assertEquals($product->getProductId()->getValue(), $decoded['productId']);
        $this->assertEquals($product->getDescription(), $decoded['description']);
    }

    /**
     * @test
     * @group product
     */
    public function getProductActionNotFound()
    {
        $route = $this->getUrl('api_get_product', ['productId' => ProductId::create()->getValue(), '_format' => 'json']);

        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     * @group product
     */
    public function postProductAction()
    {
        $route = $this->getUrl('api_post_product', ['_format' => 'json']);

        /** @var \AMD\Catalog\Domain\Model\Family\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $this->client->request(
            'POST',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'product_id' => ProductId::create()->getValue(),
                'description' => 'Product C',
                'family_id' => $family->getFamilyId()->getValue(),
            ])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED, false);
    }

    /**
     * @test
     * @group product
     */
    public function postProductActionEmptyDescriptionValidation()
    {
        $route = $this->getUrl('api_post_product', ['_format' => 'json']);

        /** @var \AMD\Catalog\Domain\Model\Family\Family $family */
        $family = $this->fixtures->getReference('family-a');

        $this->client->request(
            'POST',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'product_id' => ProductId::create()->getValue(),
                'description' => '',
                'family_id' => $family->getFamilyId()->getValue()
            ])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST, false);
    }

    /**
     * @test
     * @group product
     */
    public function postProductActionFamilyNotFound()
    {
        $route = $this->getUrl('api_post_product', ['_format' => 'json']);

        $this->client->request(
            'POST',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'product_id' => ProductId::create()->getValue(),
                'description' => '',
                'family_id' => FamilyId::create()->getValue()
            ])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND, false);
    }

    /**
     * @test
     * @group product
     */
    public function putProductActionShouldModify()
    {
        /** @var \AMD\Catalog\Domain\Model\Product\Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_put_product', ['productId' => $product->getProductId()->getValue(), '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['id' => ProductId::create(), 'description' => 'Product AA', 'family_id' => $product->getFamilyId()->getValue()])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK, false);
    }

    /**
     * @test
     * @group product
     */
    public function putProductActionBadParameters()
    {
        /** @var \AMD\Catalog\Domain\Model\Product\Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_put_product', ['productId' => $product->getProductId()->getValue(), '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['id' => ProductId::create(), 'description' => '', 'family_id' => $product->getFamilyId()->getValue()])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST, false);
    }

    /**
     * @test
     * @group product
     */
    public function putProductActionNotExists()
    {
        $route = $this->getUrl('api_put_product', ['productId' => ProductId::create()->getValue(), '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['description' => 'Product AA', 'family_id' => FamilyId::create()->getValue()])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND, false);
    }

    /**
     * @test
     * @group product
     */
    public function putProductActionFamilyNotFound()
    {
        /** @var \AMD\Catalog\Domain\Model\Product\Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_put_product', ['productId' => $product->getProductId()->getValue(), '_format' => 'json']);
        $this->client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => ProductId::create()->getValue(),
                'description' => 'Product AA',
                'family_id' => FamilyId::create()->getValue()
            ])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND, false);
    }

    /**
     * @test
     * @group product
     */
    public function deleteProductAction()
    {
        /** @var \AMD\Catalog\Domain\Model\Product\Product $product */
        $product = $this->fixtures->getReference('product-a');

        $route = $this->getUrl('api_get_product', ['productId' => $product->getProductId()->getValue(), '_format' => 'json']);

        $this->client->request('DELETE', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode(), $response->getContent());
    }

    /**
     * @test
     * @group product
     */
    public function deleteProductActionNotFound()
    {
        $route = $this->getUrl('api_get_product', ['productId' => ProductId::create()->getValue(), '_format' => 'json']);

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
