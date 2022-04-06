<?php

namespace App\Form;

use App\Entity\Campus;
use App\modele\FiltreSortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Site :',
                'required' => false,
                'attr' => ['readonly' => true
                ]])
            ->add('nom', TextType::class, [
                'label' => 'Le nom de la sortie contient :',
                 'required' => false,
            ])
            ->add('datedebut', DateType::class, [
                'label' => 'Entre',
                'html5'=>true,
                'widget'=>'single_text',
                 'required' => false,
            ])
            ->add('datefin', DateType::class, [
                'label' => 'Et',
                'html5'=>true,
                'widget'=>'single_text',
                'required' => false,
            ])
            ->add('inscrit', CheckboxType::class, [
                'label'    => 'Sorties auxquelles je suis incrite/e',
                'required' => false,
            ])
            ->add('organisateur', CheckboxType::class, [
                'label'    => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FiltreSortie::class,
        ]);
    }
}
