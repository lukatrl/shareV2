<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
            
        ]);
    }


    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('base/contact.html.twig', [
            
        ]);
    }

    #[Route('/apropos', name: 'apropos')]
    public function apropos(): Response
    {
        return $this->render('base/apropos.html.twig', [
            
        ]);
    }

    #[Route('/mention-legales', name: 'mention-legales')]
    public function metionlegales(): Response
    {
        return $this->render('base/mention-legales.html.twig', [
            
        ]);
    }
}