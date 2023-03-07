<?php

namespace App\Form;

use App\Entity\InscriptionFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('dateInscriptionFormation')
            ->add('statusFormation', ChoiceType::class, [
                'choices' => [
                    'a faire' => 'a faire',
                    'terminé' => 'terminé',


                ]])
          //  ->add('note')
            //->add('formations')
           // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InscriptionFormation::class,
        ]);
    }
}
