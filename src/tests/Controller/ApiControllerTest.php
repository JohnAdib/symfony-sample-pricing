<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiController extends WebTestCase
{
    public function testRouteApiRootUrl()
    {
        $client = static::createClient();

        // check simple get - we must get 200
        $client->request('GET', '/api');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // check simple post - we must get 404 , not 200
        $client->request('POST', '/api');
        $this->assertGreaterThan(400, $client->getResponse()->getStatusCode());

        // check simple put - we must get something else, not 200
        $client->request('PUT', '/api');
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }
}