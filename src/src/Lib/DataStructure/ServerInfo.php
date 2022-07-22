<?php
// src/Lib/DataStructure/ServerInfo.php
declare(strict_types=1);

namespace App\Lib\DataStructure;

/**
 *  Save each item of server detail
 *
 *  use php 8.1 Readonly Properties
 *  use php 8.0 Constructor property promotion
 */
class ServerInfo
{
    // // get from model
    // public readonly string $modelBrand;
    // public readonly string $modelCpu;
    // // get from ram
    // public readonly int $ramCapacity;
    // public readonly string $ramGen;
    // // get from hdd
    // public readonly int $hddCount;
    // public readonly int $hddEachCapacity;
    // public readonly int $hddTotalCapacity;
    // public readonly string $hddType;
    // // get from location
    // public readonly string $locationCity;
    // public readonly string $locationZone;
    // public readonly string $locationCode;
    // // get from price
    // public readonly string $priceCurrency;
    // public readonly int $priceAmount;

    public function __construct(
        public readonly int $index,
        public readonly ?string $model,
        public readonly ?string $ram,
        public readonly ?string $hdd,
        public readonly ?string $location,
        public readonly ?string $price,
      )
      {
        // calculate some other detail of each server and save them
        // @todo calc

      }
}