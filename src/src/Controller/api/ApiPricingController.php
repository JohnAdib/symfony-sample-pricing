<?php
// src/Controller/api/ApiPricingController.php
namespace App\Controller\api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Pricing;


class ApiPricingController extends AbstractController
{
    #[Route('/api/pricing', methods: ['GET', 'POST', 'HEAD'])]
    public function info(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        // set header for cors
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Headers:*');
        header('X-Powered-By:MrAdib');

        // get all parameters to use as filter
        $allParameters = $request->query->all();

        // call advance search
        $result = $doctrine->getManager()->getRepository(Pricing::class)->advanceSearch($allParameters);

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
    }
}