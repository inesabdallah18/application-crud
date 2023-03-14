<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmlpoyeController extends AbstractController
{
    #[Route('/emlpoye', name: 'app_emlpoye')]
    public function index(): Response
    {
        return $this->render('emlpoye/index.html.twig', [
            'controller_name' => 'EmlpoyeController',
        ]);
    }
}
