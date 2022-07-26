<?php
// src/Controller/api/ApiFiltersController.php
namespace App\Controller\api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Pricing;


class ApiFiltersController extends AbstractController
{
    #[Route('/api/filters', methods: ['GET', 'HEAD'])]
    public function info(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        // get all parameters to use as filter
        $allParameters = $request->query->all();

        // call advance search
        $result = $doctrine->getManager()->getRepository(Pricing::class)->getFilters($allParameters);

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