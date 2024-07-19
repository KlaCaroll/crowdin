<?php

namespace App\Form;

use App\Entity\Langues;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LanguesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', LanguageType::class, [
                'label' => 'Langue', 
            ])
            ->add('niveau', ChoiceType::class, [
                'choices' => [
                    'A1' => 'A1',
                    'A2' => 'A2',
                    'B1' => 'B1',
                    'B2' => 'B2',
                    'C1' => 'C1',
                    'C2' => 'C2'
                ],
                'label' => 'Votre niveau :',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Langues::class,
        ]);
    }
}
