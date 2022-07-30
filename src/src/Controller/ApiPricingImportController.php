<?php
// src/Controller/ApiPricingImportController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Lib\ReadFromExcel;


class ApiPricingImportController extends AbstractController
{
    #[Route('/api/pricing/import', methods: ['GET', 'HEAD'])]
    public function info(KernelInterface $kernel): JsonResponse
    {
        try {
            // get files url on remote and locatl
            // $url_excel_remote = $_ENV['EXCEL_URL'];

            // because that url is not exist, use local file
            $url_excel_remote = $kernel->getProjectDir() . $_ENV['EXCEL_URL_LOCAL'];

            // read data from excel
            $excelReaderObj = new ReadFromExcel($url_excel_remote);

            // read excel data
            $excelData = $excelReaderObj->fetch('Sheet2');

            // pass excel data to model to inset into db
            $model = new \App\Model\PricingModel();

            // call insert fn on model
            $model->insertDataIntoDb($excelData);

            // return json of result
            // return status 201 means created
            return $this->json("It's okay", 201);
        } catch (\exception $e) {
            // if some kind of error happend, return 506 and show error message
            return $this->json($e->getMessage(), 506);
        }
    }
}