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
                'list' => '/list',
            ],
        ];

        return $this->json($result);
    }
}