<?php
// src/Controller/api/ApiPricingImportController.php
namespace App\Controller\api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Pricing;
use App\Lib\ReadFileExcel;


class ApiPricingImportController extends AbstractController
{
    #[Route('/api/pricing/import', methods: ['GET', 'HEAD'])]
    public function info(KernelInterface $kernel, ManagerRegistry $doctrine): JsonResponse
    {
        try {
            // get files url on remote and locatl
            // $url_excel_remote = $_ENV['EXCEL_URL'];

            // because that url is not exist, use local file
            $url_excel_remote = $kernel->getProjectDir() . $_ENV['EXCEL_URL_LOCAL'];

            // read data from excel
            $excelReaderObj = new ReadFileExcel($url_excel_remote);

            // read excel data
            $excelData = $excelReaderObj->fetchDataArray('Sheet2');

            // call insert fn on model
            $this->insertDataIntoDb($excelData, $doctrine);

            // return json of result
            // return status 201 means created
            return $this->json("It's okay", 201);
        } catch (\exception $e) {
            // if some kind of error happend, return 506 and show error message
            return $this->json($e->getMessage(), 506);
        }
    }


    private function insertDataIntoDb(array $datalist, ManagerRegistry $doctrine): void
    {
        // call removeAllRecords
        $doctrine->getManager()->getRepository(Pricing::class)->removeAllRecords();

        // remove header row
        $header = array_shift($datalist);

        // reverse array order to add last one, first
        // because i think the last one is correct one and don't need old ones
        $datalist = array_reverse($datalist);

        foreach ($datalist as $row => $value) {
            // extract data and set fileds
            $pricingObj = $this->extractServerDetail($row, $value);

            // if record is duplicated, dont need to insert, continue to next one
            // in example excel data we have 13 duplicate record
            if ($this->getPricingDuplicateRecordCount($pricingObj, $doctrine)) {
                continue;
            }

            // if this server with exactly this config and currency exist,
            // only price is different
            // so decide to skip next item
            // 277 record is duplicate if we are enable this mode
            if ($this->getPricingDuplicateRecordWithoutPriceCount($pricingObj, $doctrine)) {
                continue;
            }

            // create instance of doctrine entity
            $entityManager = $doctrine->getManager();

            // tell Doctrine you want to eventually save the Pricing
            $entityManager->persist($pricingObj);

            // actually executes the queries
            $entityManager->flush();
        }
    }


    /**
     * get count of duplicate record before this
     *
     * @param  \App\Entity\Pricing                   $pricingObj
     * @param  \Doctrine\Persistence\ManagerRegistry $doctrine
     * @return boolean
     */
    private function getPricingDuplicateRecordWithoutPriceCount(Pricing $pricingObj, ManagerRegistry $doctrine): bool
    {
        $repository = $doctrine->getRepository(Pricing::class);

        $pricing = $repository->findBy([
            'model'      => $pricingObj->getModel(),
            'ram'        => $pricingObj->getRam(),
            'ramtype'    => $pricingObj->getRamtype(),
            'storagetxt' => $pricingObj->getStoragetxt(),
            'location'   => $pricingObj->getLocation(),
            'currency'   => $pricingObj->getCurrency(),
        ]);

        // if result is array, return the count of duplocate records
        if (is_array($pricing)) {
            return count($pricing);
        }

        return null;
    }


    /**
     * get count of duplicate record before this
     *
     * @param  \App\Entity\Pricing                   $pricingObj
     * @param  \Doctrine\Persistence\ManagerRegistry $doctrine
     * @return boolean
     */
    private function getPricingDuplicateRecordCount(Pricing $pricingObj, ManagerRegistry $doctrine): bool
    {
        $repository = $doctrine->getRepository(Pricing::class);

        $pricing = $repository->findBy([
            'model'      => $pricingObj->getModel(),
            'ram'        => $pricingObj->getRam(),
            'ramtype'    => $pricingObj->getRamtype(),
            'storagetxt' => $pricingObj->getStoragetxt(),
            'location'   => $pricingObj->getLocation(),
            'currency'   => $pricingObj->getCurrency(),
            'price'      => $pricingObj->getPrice(),
        ]);

        // if result is array, return the count of duplocate records
        if (is_array($pricing)) {
            return count($pricing);
        }

        return null;
    }


    /**
     * create object from pricing to insert into db
     *
     * @param  integer             $row
     * @param  array               $dataline
     * @return \App\Entity\Pricing
     */
    private function extractServerDetail(int $row, array $datarow): Pricing
    {
        // define array to save values
        $args = [];

        // cast array to object to use nullsafe operator
        $datarowObj = (object) $datarow;

        // model
        if (isset($datarow[0])) {
            $args['model'] = $datarow[0];

            // brand
            $args['brand'] = strtok($args['model'], ' ');
        }

        // ram
        if (isset($datarow[1])) {
            $ramTxt          = $datarow[1];
            $args['ram']     = intval(strtok($ramTxt, 'G'));
            $args['ramtype'] = substr($ramTxt, strlen($args['ram']) + 2);
        }

        // storage
        if (isset($datarow[2])) {
            $hddTxt = $datarow[2];
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
            $hddEachCapacity = null;
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
        }



        // location
        if (isset($datarow[3])) {
            $location = $datarow[3];
            // extract location code
            $locationCode = substr($location, -2);
            $locationDashPos = strpos($location, '-') - 3;
            if ($locationDashPos > 0) {
                // extract location iso
                $locationIso = substr($location, $locationDashPos, 3);
                // extract location cityName
                $args['city'] = substr($location, 0, $locationDashPos);
                // extract location zone
                if ($locationIso && $locationCode) {
                    $args['location'] = $locationIso . '-' . $locationCode;
                }
            }
        }



        // price
        if (isset($datarow[4])) {
            $price = $datarow[4];

            // get price amount
            $amount = preg_replace('/[^0-9\.,]*/i', '', $price);
            if ($amount) {
                $args['price'] = floatval($amount);
                // everything else is currency
                $args['currency'] = str_replace($amount, '', $price);
            }
        }




        // filter array to remove empty values
        $args = array_filter($args);

        // if we detect less than 11 fields, data is not correct
        if (count($args) !== 11) {
            var_dump($args);
            // error on data
            throw new \Exception("ExcelData-DataProblem - row " . $row);
        }

        // create new object from pricing
        $pricing = new Pricing();

        foreach ($args as $key => $value) {

            // @todo must check unique record and insert once
            // in sample data we have 14 duplicate record
            // i think it's good idea to add new field to save md5 of all fileds
            // then check it before insert new record

            // create name of method
            $methodName = 'set' . ucfirst($key);
            // call setSomefield fn with value
            $pricing->{$methodName}($value);
        }

        return $pricing;
    }
}