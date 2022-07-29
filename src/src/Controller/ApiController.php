<?php
// src/Controller/ApiController.php
namespace App\Controller;

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
                'version'  => "1.0.0",
                'date'     => date('Y/m/d H:i:s'),
                'author'   => "Javad Adib",
                'url'      => 'https://github.com/MrJavadAdib/symfony-sample-pricing',
                'examples' =>
                [
                    '/api/pricing',
                    // select or radio
                    '/api/pricing?location=AMS-01',
                    '/api/pricing?brand=Huawei',
                    '/api/pricing?hdd=sas',
                    '/api/pricing?ram=8',
                    // checkbox
                    '/api/pricing?hdd[]=ssd&hdd[]=sas',
                    '/api/pricing?ram[]=48&ram[]=64&ram[]=96',
                    // range
                    '/api/pricing?storage-min=300&storage-max=500',
                    '/api/pricing?ram-min=32&ram-max=96',
                    // multiple
                    '/api/pricing?hdd[]=sas&location=AMS-01&ram-min=32&ram-max=96',
                    '/api/pricing?hdd[]=sas&location=AMS-01&ram-min=32&ram-max=96&storage-min=300&storage-max=500',
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