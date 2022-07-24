<?php
// src/Lib/DataStructure/Server.php
declare(strict_types=1);

namespace App\Lib\DataStructure;


/**
 *  Save each item of server detail
 *
 *  Use php 8.1 Readonly Properties
 *  Use php 8.0 Constructor property promotion
 */
class Server
{
  public function __construct(
    public readonly int $index,
    // get from model
    public readonly ?string $model,
    public readonly ?string $modelBrand,
    public readonly ?string $modelCpu,
    // get from ram
    public readonly ?string $ram,
    public readonly ?int $ramCapacity,
    public readonly ?string $ramGen,
    // get from hdd
    public readonly ?string $hdd,
    public readonly ?int $hddCount,
    public readonly ?int $hddEachCapacity,
    public readonly ?int $hddTotalCapacity,
    public readonly ?string $hddType,
    // get from location
    public readonly ?string $location,
    public readonly ?string $locationCity,
    public readonly ?string $locationIso,
    public readonly ?string $locationCode,
    public readonly ?string $locationZone,
    // get from price
    public readonly ?string $price,
    public readonly ?string $priceCurrency,
    public readonly ?float $priceAmount,
  ) {
  }
}