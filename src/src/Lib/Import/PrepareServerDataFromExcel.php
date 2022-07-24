<?php
// src/Lib/Import/PrepareServerDataFromExcel.php
declare(strict_types=1);

namespace App\Lib\Import;

use App\Lib\DataStructure\Server;


/**
 *  Save each item of server detail
 *
 *  use php 8.1 Readonly Properties
 *  use php 8.0 Constructor property promotion
 */
class PrepareServerDataFromExcel
{
  /**
   * Constructor readonly properties datalist
   */
  public function __construct(public readonly array $datalist)
  {
  }


  /**
   * loop on all rows of data, clean them
   * and create instance of server obj for each row
   *
   * @return array list of objects of each server
   */
  public function dataset(): array
  {
    // variable to save final result
    $result = [];
    // variables to save filters by index
    $filterStorage  = [];
    $filterRam      = [];
    $filterHdd      = [];
    $filterLocation = [];
    $filterBrand    = [];

    // loop for each row
    foreach ($this->datalist as $row => $dataline) {
      if ($row === 0) {
        // skip first line, because contain headers
        continue;
      }
      // analyze one server and extract detail added into object
      $lineAnalyzedObj = $this->analyzeLine($row, $dataline);
      $result[] = $lineAnalyzedObj;

      // save filters by index
      $filterStorage[$lineAnalyzedObj->hddTotalCapacity][] = $lineAnalyzedObj->index;
      $filterRam[$lineAnalyzedObj->ramCapacity][]      = $lineAnalyzedObj->index;
      $filterHdd[$lineAnalyzedObj->hddType][]          = $lineAnalyzedObj->index;
      $filterLocation[$lineAnalyzedObj->locationCity][]     = $lineAnalyzedObj->index;
      $filterBrand[$lineAnalyzedObj->modelBrand][]       = $lineAnalyzedObj->index;
    }

    // save output inside array
    $output =
      [
        'servers'         => $result,
        'groupbyStorage'  => $filterStorage,
        'groupbyRam'      => $filterRam,
        'groupbyHdd'      => $filterHdd,
        'groupbyLocation' => $filterLocation,
        'groupbyBrand'    => $filterBrand,
      ];

    // return array of objects
    return $output;
  }


  /**
   * convert each line into object to pass into server class
   *
   * @param integer $index
   * @param array $datarow
   * @return object
   */
  private function analyzeLine(int $index, array $datarow): object
  {
    // cast array to object to use nullsafe operator
    $datarowObj = (object) $datarow;

    // model
    $model      = $datarowObj?->{'0'};
    $modelBrand = null;
    $modelCpu   = null;
    // ram
    $ram         = $datarowObj?->{'1'};
    $ramCapacity = null;
    $ramGen      = null;
    // hdd
    $hdd              = $datarowObj?->{'2'};
    $hddCount         = null;
    $hddEachCapacity  = null;
    $hddTotalCapacity = null;
    $hddType          = null;
    // location
    $location     = $datarowObj?->{'3'};
    $locationCity = null;
    $locationZone = null;
    $locationCode = null;
    // price
    $price         = $datarowObj?->{'4'};
    $priceCurrency = null;
    $priceAmount   = null;

    // calculate some other detail of each server and save them
    if ($model) {
      $modelBrand = strtok($model, ' ');
      $modelCpu = substr($model, strrpos($model, ' ') + 1);
    }

    if ($ram) {
      $ramCapacity = intval(strtok($ram, 'G'));
      $ramGen = substr($ram, -1);
    }

    if ($hdd) {
      $hddStr = $hdd;

      // extract hdd type
      if (substr($hddStr, -5) === 'SATA2') {
        $hddStr = substr($hddStr, 0, -5);
        $hddType = 'SATA2';
      } else if (substr($hddStr, -3) === 'SSD') {
        $hddStr = substr($hddStr, 0, -3);
        $hddType = 'SSD';
      } else if (substr($hddStr, -3) === 'SAS') {
        $hddStr = substr($hddStr, 0, -3);
        $hddType = 'SAS';
      }

      // extract hdd count
      $hddCount = intval(strtok($hddStr, 'x'));
      // calc each capacity
      $hddStr = substr($hddStr, strpos($hddStr, 'x') + 1);
      if (substr($hddStr, -2) === 'TB') {
        $hddEachCapacity = intval(substr($hddStr, 0, -2)) * 1000;
      } else if (substr($hddStr, -2) === 'GB') {
        $hddEachCapacity = intval(substr($hddStr, 0, -2));
      }

      // calc totalCapacity
      if (is_int($hddCount) && is_int($hddEachCapacity)) {
        $hddTotalCapacity = $hddCount * $hddEachCapacity;
      }
    }

    if ($location) {
      // extract location code
      $locationCode = substr($location, -2);
      // extract location zone
      $locationZone = substr($location, strpos($location, '-') - 3, 3);
      // extract location cityName
      $locationCity = substr($location, 0, strpos($location, '-') - 3);
    }

    if ($price) {
      if (mb_substr($price, 0, 1) === '€') {
        $priceCurrency = '€';
        $priceAmount = floatval(mb_substr($price, 1));
      } else if (mb_substr($price, 0, 1) === '$') {
        $priceCurrency = '$';
        $priceAmount = floatval(mb_substr($price, 1));
      } else if (mb_substr($price, 0, 2) === 'S$') {
        $priceCurrency = '$';
        $priceAmount = floatval(mb_substr($price, 2));
      }
    }

    // create array of args
    $args =
      [
        $index,
        // model
        $model,
        $modelBrand,
        $modelCpu,
        // ram
        $ram,
        $ramCapacity,
        $ramGen,
        // hdd
        $hdd,
        $hddCount,
        $hddEachCapacity,
        $hddTotalCapacity,
        $hddType,
        // location
        $location,
        $locationCity,
        $locationZone,
        $locationCode,
        // price
        $price,
        $priceCurrency,
        $priceAmount,
      ];

    // passed to server info and save object
    return new Server(...$args);
  }
}