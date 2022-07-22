<?php
// src/Controller/ApiPricingOneController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiPricingOneController extends AbstractController
{
    #[Route('/api/pricing/{id}', methods: ['GET', 'HEAD'])]
    public function show(int $id): JsonResponse
    {
        // create sample info json
        $result =
        [
            'version'  => "1.0.0",
        ];

        return $this->json($result);
    }
}