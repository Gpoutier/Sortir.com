<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>'Nom de la sortie : '
            ])
            ->add('dateHeureDebut',DateType::class,[
                'label'=>'Date et heure de la sortie : ',
                'html5'=>true,
                'widget'=>'single_text',
            ])
            ->add('dateLimiteInscription', DateType::class,[
                'label'=>'Date limite d\'inscription : ',
                'html5'=>true,
                'widget'=>'single_text',
                ])
            ->add('nbInscriptionsMax', IntegerType::class,[
                'label'=>'Nombre de places : '
                ])
            ->add('duree', IntegerType::class,[
                'label'=>'Durée : '
                ])
            ->add('infosSortie', TextareaType::class,[
                'label'=>'Description et infos : '
            ])
            ->add('ville', EntityType::class,[
                'label'=>'Ville : ',
                'class'=> Ville::class,
                'choice_label'=>'nom'
            ])
            ->add('lieu',EntityType::class,[
                'label' => 'lieu : ',
                'class' => Lieu::class,
                'choice_label' => 'nom'
            ])
            ->add('latitude',TextType::class,[
                'label' => 'latitude : '
            ])
            ->add('longitude',TextType::class,[
                'label' => 'longitude : '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}