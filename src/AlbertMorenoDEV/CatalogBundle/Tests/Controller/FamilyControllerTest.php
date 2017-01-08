<?php
namespace Tests\AlbertMorenoDEVCatalogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FamilyControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/families');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //$this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }
}