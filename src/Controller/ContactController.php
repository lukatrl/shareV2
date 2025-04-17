<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Fichier;

class ContactController extends AbstractController
{
    #[Route('/private-liste-contacts', name: 'liste-contacts')]
    public function listeContacts(EntityManagerInterface $entityManagerInterface): Response
    {
        $repoContact = $entityManagerInterface->getRepository(Contact::class);
        $contacts = $repoContact->findAll();
        return $this->render('contact/liste-contacts.html.twig', [
           'contacts' => $contacts
        ]);
    }
    #[Route('/private-utilisateurs', name: 'utilisateurs')]
    public function utilisateurs(EntityManagerInterface $entityManagerInterface): Response
    {
        
        $repoUser = $entityManagerInterface->getRepository(User::class);
        $users = $repoUser->findAll();

        // Récupérer l'espace utilisé pour chaque utilisateur
        $repoFichier = $entityManagerInterface->getRepository(Fichier::class);
        
        foreach ($users as $user) {
            
            $espaceUtilise = $repoFichier->getEspaceUtiliseParUtilisateur($user);
            $user->espaceUtilise = $espaceUtilise;  // Cette valeur est juste temporaire pour l'affichage dans le template
        }

        // Renvoyer les utilisateurs avec l'espace utilisé vers la vue
        return $this->render('contact/utilisateurs.html.twig', [
            'users' => $users  // Remarque que j'ai changé 'user' en 'users'
        ]);
    }

    #[Route('/private-profil', name: 'profil')]
    public function profil(EntityManagerInterface $entityManagerInterface): Response
    {
        $repoUser = $entityManagerInterface->getRepository(User::class);
        $user = $repoUser->findAll();
        return $this->render('contact/profil.html.twig', [
            'user' => $user
        ]);
    }
}
