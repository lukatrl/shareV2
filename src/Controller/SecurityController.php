<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, redirige-le
        if ($this->getUser()) {
            return $this->redirectToRoute('target_path');  // Remplacer 'target_path' par la route où tu veux rediriger l'utilisateur
        }

        // Récupérer les erreurs d'authentification
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupérer le dernier email utilisé
        $lastUsername = $authenticationUtils->getLastUsername();

        // Générer la question du captcha (par exemple, une addition)
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $captchaSolution = $num1 + $num2;

        // Passer les variables à la vue
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'captcha_question' => "Combien font {$num1} + {$num2} ?",  // La question du captcha
            'captcha_solution' => $captchaSolution  // La solution à vérifier côté serveur
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode est interceptée par Symfony, donc on la laisse vide
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}