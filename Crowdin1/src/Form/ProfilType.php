<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('Nom')
            ->add('prenom')
            ->add('profil', ChoiceType::class, [
                'choices' => [
                    'chef de projet' => 'chef de projet',
                    'traducteur' => 'traducteur',
                    'chef de projet/traducteur' => 'chef de projet/traducteur',
                ],
                'label' => 'Vous êtes :',
            ])
            ->add('description')
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
