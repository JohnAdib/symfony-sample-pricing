<?php
// src/Controller/ApiPricingController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Lib\LoadData;


class ApiPricingController extends AbstractController
{
    #[Route('/api/pricing')]
    public function info(): JsonResponse
    {
        // try
        {
            // read data from excel and save in array of objects
            $importObj = new LoadData();

            // get dataset
            $datalist = $importObj->dataset();

            // return json of result
            return $this->json($datalist);
        }
        // catch (\exception $e)
        // {
        //     return $this->json($e->getMessage(), $status = 506);
        // }
    }
}