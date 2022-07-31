<?php
// src/Controller/api/ApiController.php
namespace App\Controller\api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiController extends AbstractController
{
    #[Route('/api', methods: ['GET', 'HEAD'])]
    public function info(): JsonResponse
    {
        // create sample info json
        $result =
            [
                'version'     => "1.0.0",
                'date'        => date('Y/m/d H:i:s'),
                'author'      => "Javad Adib",
                'url'         => 'https://github.com/MrJavadAdib/symfony-sample-pricing',
                'importExcel' => '/api/pricing/import',
                'examples'    =>
                [
                    '/api/pricing',
                    // select or radio
                    '/api/pricing?location=SIN-11',
                    '/api/pricing?brand=Huawei',
                    '/api/pricing?hdd=sas',
                    '/api/pricing?ram=96gb',
                    // checkbox
                    '/api/pricing?hdd[]=ssd&hdd[]=sas',
                    '/api/pricing?ram[]=4&ram[]=8&ram[]=96',
                    // range
                    '/api/pricing?storage-min=300&storage-max=500',
                    '/api/pricing?ram-min=32&ram-max=96',
                    // multiple
                    '/api/pricing?hdd[]=ssd&location=AMS-01&ram-min=32&ram-max=96',
                    '/api/pricing?hdd[]=ssd&location=AMS-01&ram-min=32&ram-max=96&storage-min=200&storage-max=300',
                ],
            ];

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
    }
}