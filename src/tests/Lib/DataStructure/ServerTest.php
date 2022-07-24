<?php

declare(strict_types=1);

namespace App\Tests\Lib\DataStructure;;

use App\Lib\DataStructure\Server;
use PHPUnit\Framework\TestCase;

final class ServerTest extends TestCase
{
    public function testServerConstructor(): void
    {
        $this->assertClassHasAttribute('__construct', Server::class);
    }

    // public function testServerAttributeIndex(): void
    // {
    //     $this->assertClassHasAttribute('index', Server::class);
    // }



    // public function testCreateNewObjectFromServerByInvalidInputType(): void
    // {
    //     $args = $this->sampleObject();
    //     $args['index'] = 'abc';
    //     $this->assertIsObject(new Server(...$args));
    // }



    /**
     * @dataProvider sampleObject
     *
     * @return void
     */
    public function testCreateNewObjectFromServerIsObject(array $args): void
    {
        $data = array_values($args);
        $this->assertIsObject(new Server(...$data));
    }


    private function sampleObject(): array
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
}