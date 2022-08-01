<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiGithubControllerTest extends WebTestCase
{
    public function testRouteApiGithubRootUrl()
    {
        $client = static::createClient();

        // check simple get - we must get 302 because it will redirect to github
        $client->request('GET', '/api/github');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}