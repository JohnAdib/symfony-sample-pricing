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
//        $response = new Response('<html><body><a href="/api">Pricing API</a></body></html>');
//        $response = $this->render('index.html.twig', []);
//
//        // set cache publicly
//        $response->setPublic();
//
//        // set cache for 60 seconds = 1 minute
//        $response->setMaxAge(60);
//
//        // set a custom Cache-Control directive
//        $response->headers->addCacheControlDirective('must-revalidate', true);

        return new Response('<html><body><a href="/api">Pricing API</a></body></html>');
    }
}