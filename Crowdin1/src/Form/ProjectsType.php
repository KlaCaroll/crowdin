<?php

namespace App\Form;

use App\Entity\Projects;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;

class ProjectsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('langueoriginale', LanguageType::class, [
                'label' => 'Langue originale', 
            ])
            ->add('languetraduction1', LanguageType::class, [
                'label' => 'Langue de traduction n°1', 
            ])
            ->add('languetraduction2', LanguageType::class, [
                'label' => 'Langue de traduction n°2', 
                'placeholder' => 'Aucune',
                'required' => false,
            ])
            ->add('languetraduction3', LanguageType::class, [
                'label' => 'Langue de traduction n°3', 
                'placeholder' => 'Aucune',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
        ]);
    }
}
