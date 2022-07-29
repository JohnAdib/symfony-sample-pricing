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
    #[Route('/api/pricing', methods: ['GET', 'POST', 'HEAD'])]
    public function info(Request $request): JsonResponse
    {
        // set header for cors
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Headers:*');
        header('X-Powered-By:MrAdib');


        try {
            // read data from excel and save in array of objects
            $readertObj = new Read();

            // get all parameters to use as filter
            $allParameters = $request->query->all();

            // loop on all parameters
            foreach ($allParameters as $key => $value) {
                // it's range so use range filter for example for ?storage=100-200
                $range = explode("-", $value);
                if (is_array($range) && count($range) === 2 && is_numeric($range[0]) && is_numeric($range[1])) {
                    // add range filer
                    $readertObj->onlyFilterRange($key, $range[0], $range[1]);
                } else {
                    // check for array inside parameter for example for ?hdd=SSD|SAS|SATA2
                    $multipleItem = explode("|", $value);
                    if (is_array($multipleItem) && count($multipleItem) >= 2 && count($multipleItem) <= 10) {
                        foreach ($multipleItem as $index => $condition) {
                            // add array filter
                            $readertObj->addFilter($key, $condition);
                        }
                    } else {
                        // it's a simple filter and reset
                        $readertObj->onlyFilter($key, $value);
                    }
                }
            }

            // get all data without filter
            $result = $readertObj->fetch();

            // create json response obj
            $response = $this->json($result);

            // set cache publicly
            $response->setPublic();

            // set cache for 3600 seconds = 1 hour
            $response->setMaxAge(3600);

            // set a custom Cache-Control directive
            $response->headers->addCacheControlDirective('must-revalidate', true);

            // return response
            return $response;
        } catch (\exception $e) {
            // return error 501 and show error message
            return $this->json($e->getMessage(), $status = 501);
        }
    }
}