<?php
// src/Controller/RootController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RootController extends AbstractController
{
    #[Route('/', methods: ['GET', 'HEAD'])]
    public function info(): Response
    {
        return $this->render('index.html.twig', []);
    }
}