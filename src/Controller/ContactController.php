<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

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
        $user = $repoUser->findAll();
        return $this->render('contact/utilisateurs.html.twig', [
           'user' => $user
        ]);
    }
    #[Route('/private-profil', name: 'profil')]
    public function profil(): Response
    {
        return $this->render('contact/profil.html.twig', [

        ]);
    }
}
