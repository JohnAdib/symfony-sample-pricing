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
    // get from model
    public readonly string $modelBrand;
    public readonly string $modelCpu;
    // get from ram
    public readonly int $ramCapacity;
    public readonly string $ramGen;
    // get from hdd
    public readonly int $hddCount;
    public readonly int $hddEachCapacity;
    public readonly int $hddTotalCapacity;
    public readonly string $hddType;
    // get from location
    public readonly string $locationCity;
    public readonly string $locationZone;
    public readonly string $locationCode;
    // get from price
    public readonly string $priceCurrency;
    public readonly float $priceAmount;

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
        if($model)
        {
          $this->modelBrand = strtok($model, ' ');
          $this->modelCpu = substr($model, strrpos($model, ' ') + 1);
        }

        if($ram)
        {
          $this->ramCapacity = intval(strtok($ram, 'G'));
          $this->ramGen = substr($ram, -1);
        }
        
        if($hdd)
        {
          $hddStr = $hdd;

          // extract hdd type
          if(substr($hddStr, -5) === 'SATA2')
          {
            $hddStr = substr($hddStr, 0, -5);
            $this->hddType = 'SATA2';
          }
          else if(substr($hddStr, -3) === 'SSD')
          {
            $hddStr = substr($hddStr, 0, -3);
            $this->hddType = 'SSD';
          }
          else if(substr($hddStr, -3) === 'SAS')
          {
            $hddStr = substr($hddStr, 0, -3);
            $this->hddType = 'SAS';
          }
          
          // extract hdd count
          $this->hddCount = intval(strtok($hddStr, 'x'));
          // calc each capacity
          $hddStr = substr($hddStr, strpos($hddStr, 'x') + 1);
          if(substr($hddStr, -2) === 'TB')
          {
            $this->hddEachCapacity = intval(substr($hddStr, 0, -2)) * 1000;
          }
          else if(substr($hddStr, -2) === 'GB')
          {
            $this->hddEachCapacity = intval(substr($hddStr, 0, -2));
          }
          
          // calc totalCapacity
          if(is_int($this->hddCount) && is_int($this->hddEachCapacity))
          {
            $this->hddTotalCapacity = $this->hddCount * $this->hddEachCapacity;
          }
        }
        
        if($location)
        {
          // extract location code
          $this->locationCode = substr($location, -2);
          // extract location zone
          $this->locationZone = substr($location, strpos($location, '-') - 3, 3);
          // extract location cityName
          $this->locationCity = substr($location, 0, strpos($location, '-') - 3);
        }

        if($price)
        {
          if(mb_substr($price, 0, 1) === '€')
          {
            $this->priceCurrency = '€';
            $this->priceAmount = floatval(mb_substr($price, 1));
          }
          else if(mb_substr($price, 0, 1) === '$')
          {
            $this->priceCurrency = '$';
            $this->priceAmount = floatval(mb_substr($price, 1));
          }
          else if(mb_substr($price, 0, 2) === 'S$')
          {
            $this->priceCurrency = '$';
            $this->priceAmount = floatval(mb_substr($price, 2));
          }
        }
      }
}