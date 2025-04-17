<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Fichier;
use App\Entity\Favoris;
use App\Entity\Tag;
use App\Entity\TagFichier;
use App\Form\AjoutFichierType;

class FicherController extends AbstractController
{
    #[Route('/profil-ajout-fichier', name: 'ajout-fichier')]
    public function ajoutFichier(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $this->getUser();
        
        // Récupération du quotaMax de l'utilisateur
        $quotaMax = $user->getQuota() ? $user->getQuota()->getQuotaMax() : 0; // Assurez-vous que l'utilisateur a bien un quota
    
        // Calcul de l'espace utilisé par l'utilisateur
        $repoFichier = $entityManagerInterface->getRepository(Fichier::class);
        $espaceUtilise = $repoFichier->getEspaceUtiliseParUtilisateur($user);
    
        // Création du formulaire pour l'ajout de fichier
        $form = $this->createForm(AjoutFichierType::class);
    
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $fichierUpload = $form->get('fichier')->getData();
    
                if ($fichierUpload) {
                    $tailleFichier = $fichierUpload->getSize();
    
                    // Vérification du quota avant d'ajouter le fichier
                    if ($espaceUtilise + $tailleFichier > $quotaMax) {
                        $this->addFlash('error', 'Vous avez atteint votre quota de stockage !');
                        return $this->redirectToRoute('ajout-fichier');
                    }
    
                    // Gestion de l'upload du fichier
                    $nomFichier = pathinfo($fichierUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    $nomFichier = $slugger->slug($nomFichier) . '-' . uniqid() . '.' . $fichierUpload->guessExtension();
    
                    try {
                        $fichier = new Fichier();
                        $fichier->setNomServeur($nomFichier);
                        $fichier->setNomOriginal($fichierUpload->getClientOriginalName());
                        $fichier->setDateEnvoi(new \DateTime());
                        $fichier->setExtension($fichierUpload->guessExtension());
                        $fichier->setTaille($tailleFichier);
                        $fichier->setProprietaire($user);
    
                        $fichierUpload->move($this->getParameter('file_directory'), $nomFichier);
    
                        // Gestion des tags (vérifie si l'utilisateur a sélectionné un tag)
                        $tagsSelectionnes = $form->get('tags')->getData();
                        if (!empty($tagsSelectionnes)) {
                            foreach ($tagsSelectionnes as $tag) {
                                if ($tag->getNom() !== '') { // On ignore les tags vides
                                    $tagFichier = new TagFichier();
                                    $tagFichier->setTag($tag);
                                    $tagFichier->setFichier($fichier);
                                    $entityManagerInterface->persist($tagFichier);
                                }
                            }
                        }
    
                        // Vérifier si un nouveau tag a été ajouté
                        $nouveauTagNom = $form->get('nouveauTag')->getData();
                        if (!empty($nouveauTagNom)) {
                            $tagRepo = $entityManagerInterface->getRepository(Tag::class);
                            $tagExistant = $tagRepo->findOneBy(['nom' => $nouveauTagNom]);
    
                            if (!$tagExistant) {
                                $nouveauTag = new Tag();
                                $nouveauTag->setNom($nouveauTagNom);
                                $nouveauTag->setCouleur(sprintf('#%06X', mt_rand(0, 0xFFFFFF))); // Génération couleur random
                                $entityManagerInterface->persist($nouveauTag);
    
                                $tagFichier = new TagFichier();
                                $tagFichier->setTag($nouveauTag);
                                $tagFichier->setFichier($fichier);
                                $entityManagerInterface->persist($tagFichier);
                            }
                        }
    
                        // Sauvegarde du fichier et des modifications dans la base de données
                        $entityManagerInterface->persist($fichier);
                        $entityManagerInterface->flush();
    
                        $this->addFlash('notice', 'Fichier et tags ajoutés avec succès !');
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Erreur lors de l\'envoi du fichier');
                    }
                }
    
                return $this->redirectToRoute('ajout-fichier');
            }
        }
    
        // Calcul de l'espace restant pour l'utilisateur
        $espaceRestant = $quotaMax - $espaceUtilise;
    
        // Envoi des données au template
        return $this->render('ficher/ajout-fichier.html.twig', [
            'form' => $form->createView(),
            'quotaMax' => $quotaMax,
            'espaceUtilise' => $espaceUtilise,
            'espaceRestant' => $espaceRestant,
        ]);
    }


    #[Route('/profil-telechargement-fichier/{id}', name: 'telechargement-fichier', requirements: ["id" => "\d+"])]
    public function telechargementFichier(int $id, EntityManagerInterface $entityManagerInterface): Response
    {
        $repoFichier = $entityManagerInterface->getRepository(Fichier::class);
        $fichier = $repoFichier->find($id);

        if (!$fichier) {
            return $this->redirectToRoute('ajout-fichier');
        }

        return $this->file($this->getParameter('file_directory') . '/' . $fichier->getNomServeur(), $fichier->getNomOriginal());
    }

    #[Route('/fav/{id}', name: 'app_fav_fichier')]
    public function favFichier(Fichier $fichier, EntityManagerInterface $emi): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour ajouter un favori.');
            return $this->redirectToRoute('ajout-fichier');
        }

        $repoFavoris = $emi->getRepository(Favoris::class);
        $favori = $repoFavoris->findOneBy([
            'user' => $user,
            'fichier' => $fichier
        ]);

        if ($favori) {
            $emi->remove($favori);
            $this->addFlash('notice', 'Fichier retiré des favoris');
        } else {
            $favori = new Favoris();
            $favori->setUser($user);
            $favori->setFichier($fichier);

            $emi->persist($favori);
            $this->addFlash('notice', 'Fichier ajouté aux favoris');
        }

        $emi->flush();
        return $this->redirectToRoute('ajout-fichier');
    }
}
