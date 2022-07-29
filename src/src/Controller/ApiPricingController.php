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

                if (is_array($value)) {
                    // if value is array, for chedckbox
                    foreach ($value as $condition) {
                        // add array filter
                        $readertObj->addFilter($key, $condition);
                    }
                } else {
                    // check value is numberic for range - for range slider
                    // based on min or max on filed, call fn
                    if (substr($key, -3) === 'min') {
                        // for example storage-min
                        $readertObj->onlyFilterRangeMin(substr($key, 0, -4), $value);
                    } else if (substr($key, -3) === 'max') {
                        // for example storage-max
                        $readertObj->onlyFilterRangeMax(substr($key, 0, -4), $value);
                    } else {
                        // for dropdown or radio with numeric value
                        $readertObj->onlyFilter($key, $value);
                    }
                }
            }

            // get all data without filter
            $result = $readertObj->fetch();

            // create json response obj
            $response = $this->json($result);

            // set cache publicly
            // $response->setPublic();

            // set cache for 3600 seconds = 1 hour
            // $response->setMaxAge(3600);

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