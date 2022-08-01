<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RootControllerTest extends WebTestCase
{
    public function testRouteRootUrl()
    {
        $client = static::createClient();

        // check simple get - we must get 200
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }
}