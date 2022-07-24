<?php
// src/Controller/ApiController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiController extends AbstractController
{
    #[Route('/api')]
    public function info(): JsonResponse
    {
        // create sample info json
        $result =
            [
                'version'  => "1.0.0",
                'date'     => date('Y/m/d H:i:s'),
                'author'   => "Javad Adib",
                'url'      => 'https://github.com/MrJavadAdib/symfony-sample-pricing',
                'examples' =>
                [
                    'pricing-all' => '/api/pricing',
                    // one filter
                    'pricing-hdd' => '/api/pricing?hdd=ssd',
                    'pricing-brand' => '/api/pricing?brand=HP',
                    'pricing-ram' => '/api/pricing?ram=8',
                    'pricing-location' => '/api/pricing?location=AmsterdamAMS-01',
                    // one filter - multiple
                    'pricing-ram[multiple]' => '/api/pricing?ram=8|32|64',
                    'pricing-hdd[multiple]' => '/api/pricing?hdd=ssd|sas',
                    // one filter - range
                    'pricing-storge[range]' => '/api/pricing?storage=960-1500',
                    'pricing-price[range]' => '/api/pricing?price=100-900',
                    // multiple
                    'pricing-hdd-storage[range]' => '/api/pricing?hdd=ssd&storage=300-500',
                    'pricing-hdd[multiple]-storage[range]' => '/api/pricing?hdd=ssd|sas&storage=300-1000',
                    // all
                    'pricing-hdd[multiple]-storage[range]-location-ram' => '/api/pricing?hdd=ssd|sas&storage=400-1000&AmsterdamAMS-01&ram=64',
                    'pricing-hdd[multiple]-storage[range]-location-ram-brand[multiple]-price[range]' => '/api/pricing?hdd=ssd|sas&storage=400-1600&AmsterdamAMS-01&ram=96&brand=hp|dell&price=100-600',

                ],
            ];

        return $this->json($result);
    }
}