<?php

namespace App\Form;

use App\Entity\CategorieFormation;
use App\Entity\Category;
use App\Entity\Centre;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomFormation')
            ->add('descriptionFormation')
            ->add('coutFormation')
            ->add('NombreDePlace')
            ->add('duree')
            ->add('iamgeformation', FileType::class, [
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
            ##->add('idCategorieFormation')
            ->add('idCategorieFormation',EntityType::class,['class' => CategorieFormation::class,
                'choice_label' => 'nomCategorieFormation'  ])
            ##->add('idCentre')
        ->add('idCentre',EntityType::class,['class' => Centre::class,
        'choice_label' => 'nomCentre'  ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
