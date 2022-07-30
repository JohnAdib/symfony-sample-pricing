<?php
// src/Model/Pricing.php
namespace App\Model;

use App\Entity\Pricing;
use Doctrine\Persistence\ManagerRegistry;

class PricingModel
{
    private $DOCTRINE;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->DOCTRINE = $doctrine;
    }


    public function insertDataIntoDb(array $datalist): void
    {
        // create collection to insert into db
        $entityManager = $this->DOCTRINE->getManager();

        foreach ($this->datalist as $row => $dataline) {
            if ($row === 0) {
                // skip first line, because contain headers
                continue;
            }

            // define array to save values
            $args = [];
            // create new object from pricing
            $pricing = new Pricing();

            // cast array to object to use nullsafe operator
            $datarowObj = (object) $dataline;


            // model
            $args['model'] = $datarowObj?->{'0'};

            // brand
            $args['brand'] = strtok($args['model'], ' ');

            // ram
            $ramTxt          = $datarowObj?->{'1'};
            $args['rm']      = intval(strtok($ramTxt, 'G'));
            $args['ramtyle'] = strtok($ramTxt, 'B');

            // storage
            $hddTxt = $datarowObj?->{'2'};
            $args['storagetxt'] = $hddTxt;
            // extract storage type
            if (substr($hddTxt, -5) === 'SATA2') {
                $hddTxt = substr($hddTxt, 0, -5);
                $args['storagetype'] = 'SATA';
            } else if (substr($hddTxt, -3) === 'SSD') {
                $hddTxt = substr($hddTxt, 0, -3);
                $args['storagetype'] = 'SSD';
            } else if (substr($hddTxt, -3) === 'SAS') {
                $hddTxt = substr($hddTxt, 0, -3);
                $args['storagetype'] = 'SAS';
            }
            // extract hdd count
            $hddCount = intval(strtok($hddTxt, 'x'));
            // calc each capacity
            $hddTxt = substr($hddTxt, strpos($hddTxt, 'x') + 1);
            if (substr($hddTxt, -2) === 'TB') {
                $hddEachCapacity = intval(substr($hddTxt, 0, -2)) * 1000;
            } else if (substr($hddTxt, -2) === 'GB') {
                $hddEachCapacity = intval(substr($hddTxt, 0, -2));
            }
            // calc totalCapacity
            if (!is_int($hddCount) || !is_int($hddEachCapacity)) {
                // count or capacity is not int
                // thrown error
            }
            $args['storage'] = $hddCount * $hddEachCapacity;

            // location
            $location = $datarowObj?->{'3'};
            // extract location code
            $locationCode = substr($location, -2);
            // extract location iso
            $locationIso = substr($location, strpos($location, '-') - 3, 3);
            // extract location cityName
            $args['city'] = substr($location, 0, strpos($location, '-') - 3);
            // extract location zone
            $args['location'] = $locationIso . '-' . $locationCode;

            // price
            $price = $datarowObj?->{'4'};
            if (!$price) {
                // thrown error
            }
            if (mb_substr($price, 0, 1) === '€') {
                $args['currency'] = '€';
                $args['price'] = floatval(mb_substr($price, 1));
            } else if (mb_substr($price, 0, 1) === '$') {
                $args['currency'] = '$';
                $args['price'] = floatval(mb_substr($price, 1));
            } else if (mb_substr($price, 0, 2) === 'S$') {
                $args['currency'] = '$';
                $args['price'] = floatval(mb_substr($price, 2));
            }

            var_dump($args);
            exit();

            // set values into class
            // $pricing->setModel($model);
            // $pricing->setBrand($brand);


            // price

        }

        var_dump($datalist);

        // calculate some other detail of each server and save them
        // if (!$model) {
        //     throw new \Exception("ExcelData-NotExit-Model - row " . $row);
        // }

        exit();
    }
}