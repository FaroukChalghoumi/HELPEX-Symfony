<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Filiere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;




class UserProType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ajoutez un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'minimum 6 characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'New password',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'les mots de passes doivent etre identiques.',
            ])
            ->add('Nom')
            ->add('Prenom')
            ->add('sexe',ChoiceType::class, [
                'choices' => [
                    'homme' => 'homme',
                    'femme' => 'femme',
    
                ]])
            ->add('adresse',ChoiceType::class, [
                'choices' => [
                    'bizerte' => 'bizerte',
                    'tunis' => 'tunis',
                    'ariana' => 'ariana',
                    'sousse' => 'sousse',
                    'sfax' => 'sfax',
                    'gabes' => 'gabes',
                    

                    
                    

    
                ]])
            ->add('num_tel')
            ->add('pic',  FileType::class, [
                'label' => 'Votre image de profil',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'insérer une image de type png,jpg ou jpeg',
                    ]) 
                ],
            ] )
            ->add('bio', TextareaType:: class)
            ->add('date_naissance')
          
            ->add('certif',   FileType::class, [
                'label' => 'Votre diplome ou certification (pdf)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'votre diplome ou certificat est obligatoire.',
                    ]),
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'il faut un fichier pdf',
                    ]) 
                ],
            ] )
            ->add('tarif')
            ->add('filiere')
           
            
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }


   

}
