<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Lib\Import\FromExcel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiPricingController extends WebTestCase
{
    public function testRouteApiRootUrl()
    {
        $client = static::createClient();

        // check simple get - we must get 200
        $client->request('GET', '/api/pricing');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $getResponseJson = $client->getResponse()->getContent();
        // $getResponseJson = json_decode($getResponseJson, true);

        // check simple post - we must get 404 , not 200
        $client->request('POST', '/api/pricing');
        $this->assertGreaterThan(400, $client->getResponse()->getStatusCode());

        // check simple put - we must get something else, not 200
        $client->request('PUT', '/api/pricing');
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());


        $reader = new FromExcel();
        $filePathJson = $reader->ADDR_ABSOLUTE_JSON;
        $this->assertJsonStringEqualsJsonFile($filePathJson, $getResponseJson);
    }
}