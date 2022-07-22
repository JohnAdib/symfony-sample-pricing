<?php
// src/Controller/ApiPricingController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Lib\ImportFromExcel;


class ApiPricingController extends AbstractController
{
    #[Route('/api/pricing')]
    public function info(): JsonResponse
    {
        // read data from excel and save in array of objects
        $importObj = new ImportFromExcel();
        $datalist = $importObj->import();

        // return json of result
        return $this->json($datalist);
    }
}