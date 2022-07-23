<?php
// src/Controller/ApiPricingController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Lib\Reader\Read;


class ApiPricingController extends AbstractController
{
    #[Route('/api/pricing')]
    public function info(): JsonResponse
    {
        // try
        {
            // read data from excel and save in array of objects
            $readertObj = new Read();

            // apply some filter for example
            // get filter by ram 4
            $datalist = $readertObj->addFilter('ram', '32');
            $datalist = $readertObj->addFilter('ram', 64);
            $datalist = $readertObj->addFilter('location', 'AmsterdamAMS-01');
            $datalist = $readertObj->addFilter('hdd', 'SSD');

            // symfony.mradib.com/api/pricing?location=AmsterdamAMS-01&hdd=SSD&ram=32|64&storage=500-2000

            // force only once - example
            // $datalist = $readertObj->onlyFilter('ram', '128');

            // add range for storage
            $datalist = $readertObj->onlyFilterRange('storage', 50, 1000);



            // get all data without filter
            $datalist = $readertObj->fetch();

            // return json of result
            return $this->json($datalist);
        }
        // catch (\exception $e)
        // {
        //     return $this->json($e->getMessage(), $status = 506);
        // }
    }
}