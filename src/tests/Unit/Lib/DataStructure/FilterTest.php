<?php

declare(strict_types=1);

namespace App\Tests\Unit\Lib\DataStructure;;

use App\Lib\DataStructure\Filter;
use PHPUnit\Framework\TestCase;

final class FilterTest extends TestCase
{

    public function testCreateNewObjectFromFilter(): void
    {
        $validData = $this->sampleValidArray();
        // check count of valid data is 20
        $this->assertCount(20, $validData);

        // create instance of server with valid data
        $myFilterObj = new Filter(...$validData);

        // check object is instance of server
        $this->assertInstanceOf(Filter::class, $myFilterObj);

        // chech it's object
        $this->assertIsObject($myFilterObj);
        // check data
        // model
        $this->assertSame('Dell R210Intel Xeon X3440', $myFilterObj->model);
        $this->assertSame('Dell', $myFilterObj->modelBrand);
        // ram
        $this->assertSame('16GBDDR3', $myFilterObj->ram);
        $this->assertSame(16, $myFilterObj->ramCapacity);
        // hdd
        $this->assertSame('2x2TBSATA2', $myFilterObj->hdd);
        $this->assertSame(4000, $myFilterObj->hddTotalCapacity);
        $this->assertSame('SATA2', $myFilterObj->hddType);
        // location
        $this->assertSame('AmsterdamAMS-01', $myFilterObj->location);
        $this->assertSame('AMS-01', $myFilterObj->locationZone);
        // price
        $this->assertSame('€49.99', $myFilterObj->price);
        $this->assertSame(49.99, $myFilterObj->priceAmount);

        // check filter - hdd - false
        $myFilters = ['hdd' => ['sas']];
        $this->assertFalse($myFilterObj->validate($myFilters));

        // check filter - hdd - false
        $myFilters = ['hdd' => ['SATA2']];
        $this->assertFalse($myFilterObj->validate($myFilters));

        // check filter - hdd - true
        $myFilters = ['hdd' => ['sata2']];
        $this->assertTrue($myFilterObj->validate($myFilters));

        // check filter - hdd - true
        $myFilters = ['hdd' => ['sata2', 'ssd']];
        $this->assertTrue($myFilterObj->validate($myFilters));

        // check filter - storage range - true
        $myFilters = ['storage' => ['min' => '2000', 'max' => '5000']];
        $this->assertTrue($myFilterObj->validate($myFilters));

        // check filter - storage range - false
        $myFilters = ['storage' => ['min' => '100', 'max' => '400']];
        $this->assertFalse($myFilterObj->validate($myFilters));
    }


    public function sampleValidArray(): array
    {
        return
            [
                1,
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
                "AMS-01",
                "€49.99",
                "€",
                49.99

            ];
    }
}