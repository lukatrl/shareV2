<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
// use Symfony\Component\Mime\Email; (la ligne est inutile)
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class BaseController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
            
        ]);
    }


    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response    {
        $form = $this->createForm(ContactType::class);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $email = (new TemplatedEmail())
                ->from($form->get('email')->getData())
                ->to('reply@nuage-pedagogique.fr')
                ->subject($form->get('sujet')->getData())
                ->htmlTemplate('emails/email.html.twig')
                ->context([
                    'nom'=> $form->get('nom')->getData(),
                    'sujet'=> $form->get('sujet')->getData(),
                    'message'=> $form->get('message')->getData()
                ]);
              
                $mailer->send($email);
                $this->addFlash('notice','Message envoyé');
                return $this->redirectToRoute('contact');
            }
        }

        return $this->render('base/contact.html.twig', [
            'form' => $form->createView()
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