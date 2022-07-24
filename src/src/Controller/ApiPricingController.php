<?php
// src/Controller/ApiPricingController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Lib\Reader\Read;


class ApiPricingController extends AbstractController
{
    #[Route('/api/pricing', methods: ['GET', 'HEAD'])]
    public function info(Request $request): JsonResponse
    {
        try {
            // read data from excel and save in array of objects
            $readertObj = new Read();

            // get all parameters to use as filter
            $allParameters = $request->query->all();

            // loop on all parameters
            foreach ($allParameters as $key => $value) {
                // it's range so use range filter for example for ?storage=100-200
                $range = explode("-", $value);
                if (is_array($range) && count($range) === 2) {
                    $datalist = $readertObj->onlyFilterRange($key, $range[0], $range[1]);
                } else {
                    // check for array inside parameter for example for ?hdd=SSD|SAS|SATA2
                    $multipleItem = explode("|", $value);
                    if (is_array($multipleItem) && count($multipleItem) >= 2 && count($multipleItem) <= 10) {
                        foreach ($multipleItem as $index => $condition) {
                            $datalist = $readertObj->addFilter($key, $condition);
                        }
                    } else {
                        // it's a simple filter and reset
                        $datalist = $readertObj->onlyFilter($key, $value);
                    }
                }
            }

            // get all data without filter
            $datalist = $readertObj->fetch();

            // return json of result
            return $this->json($datalist, $status = 200);
        } catch (\exception $e) {
            // return error 501 and show error message
            return $this->json($e->getMessage(), $status = 501);
        }
    }
}