<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\AjoutFichierType;
use App\Entity\Fichier;
use Doctrine\ORM\EntityManagerInterface;

class FicherController extends AbstractController
{
    #[Route('/profil-ajout-fichier', name: 'ajout-fichier')]
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(AjoutFichierType::class);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $fichier = $form->get('fichier')->getData();
                
                if($fichier){
                 $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                 $nomFichier = $slugger->slug($nomFichier);
                 $nomFichier = $nomFichier.'-'.uniqid().'.'.$fichier->guessExtension();
                    try{
                        $f = new Fichier();
                        $f->setNomServeur($nomFichier);
                        $f->setNomOriginal($fichier->getClientOriginalName());
                        $f->setDateEnvoi(new \Datetime());
                        $f->setExtension($fichier->guessExtension());
                        $f->setTaille($fichier->getSize());
                        $f->setProprietaire($this->getUser());

                        $fichier->move($this->getParameter('file_directory'), $nomFichier);
                        $this->addFlash('notice', 'Fichier envoyé');
                        $entityManagerInterface->persist($f);
                        $entityManagerInterface->flush();
                    }
                    catch(FileException $e){
                        $this->addFlash('notice', 'Erreur d\'envoi');
                    }        
                }
                return $this->redirectToRoute('ajout-fichier');
            }
        }        
        return $this->render('ficher/ajout-fichier.html.twig', [
            'form'=> $form->createView()
        ]);
    }
    
    #[Route('/profil-telechargement-fichier/{id}', name: 'telechargement-fichier', requirements: ["id"=>"\d+"] )]
    public function telechargementFichier(int $id, EntityManagerInterface $entityManagerInterface) {
     
        $repoFichier = $entityManagerInterface->getRepository(Fichier::class); 
        $fichier = $repoFichier->find($id);
        if ($fichier == null){
            $this->redirectToRoute('ajout_fichier'); }
        else{
            return $this->file($this->getParameter('file_directory').'/'.$fichier->getNomServeur(), $fichier->getNomOriginal());
        } 
    } 
}
