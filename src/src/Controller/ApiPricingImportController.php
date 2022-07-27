<?php
// src/Controller/ApiPricingImportController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Lib\Import\FromExcel;


class ApiPricingImportController extends AbstractController
{
    #[Route('/api/pricing/import', methods: ['GET', 'HEAD'])]
    public function info(): JsonResponse
    {
        try {
            // read data from excel and save in array of objects
            new FromExcel();

            // return json of result
            // return status 201 means created
            return $this->json("It's okay", $status = 201);
        } catch (\exception $e) {
            // if some kind of error happend, return 506 and show error message
            return $this->json($e->getMessage(), $status = 506);
        }
    }
}