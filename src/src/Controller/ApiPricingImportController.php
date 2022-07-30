<?php
// src/Controller/ApiPricingImportController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Lib\Import\FromExcel;
use App\Lib\ReadFromExcel;


class ApiPricingImportController extends AbstractController
{
    #[Route('/api/pricing/import', methods: ['GET', 'HEAD'])]
    public function info(KernelInterface $kernel): JsonResponse
    {
        try {
            // get files url on remote and locatl
            $url_excel_remote = $_ENV['EXCEL_URL'];
            $url_excel_remote = $kernel->getProjectDir() . $_ENV['EXCEL_URL_LOCAL'];
            $url_temp         = $kernel->getProjectDir() . $_ENV['EXCEL_ADDR_TEMP'];

            var_dump($url_excel_remote);
            var_dump($url_temp);


            // read data from excel
            $excelReaderObj = new ReadFromExcel($url_excel_remote, $url_temp);
            // read excel data
            $excelData = $excelReaderObj->fetch('Sheet2');

            // pass excel data to model to inset into db

            // return json of result
            // return status 201 means created
            return $this->json("It's okay", 201);
        } catch (\exception $e) {
            // if some kind of error happend, return 506 and show error message
            return $this->json($e->getMessage(), 506);
        }
    }
}