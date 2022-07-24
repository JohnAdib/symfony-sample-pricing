<?php

declare(strict_types=1);

namespace App\Tests\Lib\DataStructure;;

use App\Lib\DataStructure\Server;
use PHPUnit\Framework\TestCase;

final class ServerTest extends TestCase
{
    public function testServerCheckAllAttributesExist(): void
    {
        $this->assertClassHasAttribute('index', Server::class);
        $this->assertClassHasAttribute('model', Server::class);
        $this->assertClassHasAttribute('modelBrand', Server::class);
        $this->assertClassHasAttribute('modelCpu', Server::class);
        $this->assertClassHasAttribute('ram', Server::class);
        $this->assertClassHasAttribute('ramCapacity', Server::class);
        $this->assertClassHasAttribute('ramGen', Server::class);
        $this->assertClassHasAttribute('hdd', Server::class);
        $this->assertClassHasAttribute('hddCount', Server::class);
        $this->assertClassHasAttribute('hddEachCapacity', Server::class);
        $this->assertClassHasAttribute('hddTotalCapacity', Server::class);
        $this->assertClassHasAttribute('hddType', Server::class);
        $this->assertClassHasAttribute('location', Server::class);
        $this->assertClassHasAttribute('locationCity', Server::class);
        $this->assertClassHasAttribute('locationZone', Server::class);
        $this->assertClassHasAttribute('locationCode', Server::class);
        $this->assertClassHasAttribute('price', Server::class);
        $this->assertClassHasAttribute('priceCurrency', Server::class);
        $this->assertClassHasAttribute('priceAmount', Server::class);
    }


    /**
     * @dataProvider sampleDataProvider
     *
     * @return void
     */
    public function testCreateNewObjectFromServerObjectWithDataProvider(array $args): void
    {
        $data = array_values($args);
        $this->assertIsObject(new Server(...$data));
    }


    public function sampleDataProvider(): array
    {
        return
            [
                'real data okay' => [
                    [
                        "index"            => 1,
                        "model"            => "Dell R210Intel Xeon X3440",
                        "modelBrand"       => "Dell",
                        "modelCpu"         => "X3440",
                        "ram"              => "16GBDDR3",
                        "ramCapacity"      => 16,
                        "ramGen"           => "3",
                        "hdd"              => "2x2TBSATA2",
                        "hddCount"         => 2,
                        "hddEachCapacity"  => 2000,
                        "hddTotalCapacity" => 4000,
                        "hddType"          => "SATA2",
                        "location"         => "AmsterdamAMS-01",
                        "locationCity"     => "Amsterdam",
                        "locationZone"     => "AMS",
                        "locationCode"     => "01",
                        "price"            => "€49.99",
                        "priceCurrency"    => "€",
                        "priceAmount"      => 49.99
                    ]

                ],
                'sample input 2' => [
                    [
                        "index"            => 1,
                        "model"            => "Dell R210Intel Xeon X3440",
                        "modelBrand"       => "Dell",
                        "modelCpu"         => "X3440",
                        "ram"              => "16GBDDR3",
                        "ramCapacity"      => 64,
                        "ramGen"           => "3",
                        "hdd"              => "2x2TBSATA2",
                        "hddCount"         => 2,
                        "hddEachCapacity"  => 2000,
                        "hddTotalCapacity" => 4000,
                        "hddType"          => "SATA2",
                        "location"         => "AmsterdamAMS-01",
                        "locationCity"     => "Amsterdam",
                        "locationZone"     => "AMS",
                        "locationCode"     => "01",
                        "price"            => "$49.99",
                        "priceCurrency"    => "$",
                        "priceAmount"      => 149.99
                    ]
                ],
            ];
    }



    public function testCreateNewObjectFromServerObjectInvalidArray(): void
    {
        // expect type error because index is not int and string is passed
        $this->expectError();

        $data = array_values($this->sampleInvalidArray());
        $this->assertIsObject(new Server(...$data));
    }


    public function sampleInvalidArray(): array
    {
        return
            [
                '1',
                "Dell R210Intel Xeon X3440",
                "Dell",
                "X3440",
                "16GBDDR3",
                16,
                "3",
                "2x2TBSATA2",
                2,
                2000,
                4000,
                "SATA2",
                "AmsterdamAMS-01",
                "Amsterdam",
                "AMS",
                "01",
                "€49.99",
                "€",
                49.99

            ];
    }


    public function testCreateNewObjectFromServerWithOutData(): void
    {
        // expect type error because index is not int and string is passed
        $this->expectError();

        $this->assertIsObject(new Server());
    }



    public function testCreateNewObjectFromServerWithIncorrectData(): void
    {
        // expect type error because index is not int and string is passed
        $this->expectError();

        $this->assertIsObject(new Server(1, 2, 3));
    }
}