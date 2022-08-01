<?php
// src/Controller/ApiGithubController.php
namespace App\Controller\api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiGithubController extends AbstractController
{
    #[Route('/api/github', methods: ['GET'])]
    public function info(): RedirectResponse
    {
        return new RedirectResponse('https://github.com/MrJavadAdib/symfony-sample-pricing');
    }
}