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

            // for each parameter passed through url
            foreach ($allParameters as $key => $value) {

                // chcek value is numberic for range - for range slider
                if (is_numeric($value)) {
                    // get 3 last char for min and max
                    $lastCharMinMax = substr($key, -3);
                    // convert value to int
                    $valueNumber = intval($value);
                    // extract field name
                    $field = substr($key, 0,  -3);
                    // based on min or max on filed, call fn
                    if ($lastCharMinMax === 'min') {
                        $readertObj->onlyFilterRangeMin($field, $valueNumber);
                    } else if ($lastCharMinMax === 'max') {
                        $readertObj->onlyFilterRangeMax($field, $valueNumber);
                    }
                } else if (is_array($value)) {
                    // if value is array, for chedckbox
                    foreach ($value as $condition) {
                        // add array filter
                        $readertObj->addFilter($key, $condition);
                    }
                } else {
                    // for dropdown or radio
                    $readertObj->onlyFilter($key, $value);
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