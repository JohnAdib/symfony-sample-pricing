<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Lib\Import\FromExcel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiPricingControllerTest extends WebTestCase
{

    private const TOTAL_RECORD_FILTER_HDD_SAS = 11;


    public function testRouteApiPricingUrl(): void
    {
        $client = static::createClient();

        // check simple get - we must get 200
        $client->request('GET', '/api/pricing');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // response successfull
        $this->assertResponseIsSuccessful();

        $getResponseJson = $client->getResponse()->getContent();
        // $getResponseJson = json_decode($getResponseJson, true);

        $reader = new FromExcel();
        $filePathJson = $reader->ADDR_ABSOLUTE_JSON;
        // $this->assertJsonStringEqualsJsonFile($filePathJson, $getResponseJson);
    }


    public function testRouteApiPricing(): void
    {
        $client = static::createClient();

        // the required HTTP_X_REQUESTED_WITH header is added automatically
        $client->xmlHttpRequest('GET', '/api/pricing', ['hdd' => 'SAS']);

        // check request header
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // get contetnt of resutl
        $getResponse = $client->getResponse()->getContent();
        $getResponseJson = json_decode($getResponse, true);

        // check count of json
        $this->assertCount(self::TOTAL_RECORD_FILTER_HDD_SAS, $getResponseJson);
    }
}