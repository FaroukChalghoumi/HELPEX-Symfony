<?php

namespace App\Form;

use App\Entity\Produits;
use App\Config\EtatProduit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomProduit')
            //->add('EtatProduit')
//            ->add('EtatProduit', EnumType::class, ['class' => EtatProduit::class])
            ->add('EtatProduit', ChoiceType::class, [
            'choices' => [
                'New' => 'New Product',
                'UsedNEW' => 'UsedNEW',
                'UsedGood' => 'UsedGood',

            ]])

            //this class is defined to perform the enum type
            //Be careful ! This only works in php 8.1 >
            //If this caused any problems make sure to check your php -version
            // with love farouk <3
            ->add('PrixProduit')
            ->add('DescriptionProduit')
            /*-> add('ImagePath', FileType::class, [
                'label' => 'Image Produit (Image file)',

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
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])*/
            ->add('ImagePath', FileType::class, [
                'label' => 'Picture (JPEG, PNG or GIF file)',
                'mapped' => false,
                'required' => false,

                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])


            ->add('localisationProduit')
            ->add('Brand')
            ->add('CategorieProduit')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
