<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;

class ContactController extends AbstractController
{
    #[Route('/liste-contacts', name: 'liste-contacts')]
    public function listeContacts(EntityManagerInterface $entityManagerInterface): Response
    {
        $repoContact = $entityManagerInterface->getRepository(Contact::class);
        $contacts = $repoContact->findAll();
        return $this->render('contact/liste-contacts.html.twig', [
           'contacts' => $contacts
        ]);
    }
}
