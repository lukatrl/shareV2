<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class AjoutFichierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fichier', FileType::class, array('label' => 'Fichier à télécharger',
            'constraints' => [
                new File([
                    'maxSize' => '1000k',
                    'mimeTypes' => [
                        'application/pdf',
                        'application/x-pdf',
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Le site accepte uniquement les fichiers PDF, PNG et JPG',
                ])
            ],))
            ->add('envoyer', SubmitType::class, ['attr' => ['class'=> 'btn bg-primary text-white m-4' ], 'row_attr' => ['class' => 'text-center'],])
        ;
  }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
