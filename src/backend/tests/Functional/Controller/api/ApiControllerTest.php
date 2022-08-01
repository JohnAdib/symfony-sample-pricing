<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testRouteApiRootUrl()
    {
        $client = static::createClient();

        // check simple get - we must get 200
        $client->request('GET', '/api');
        $this->assertResponseIsSuccessful();
    }
}