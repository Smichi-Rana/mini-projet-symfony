<?php

namespace App\Form;
use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
class VoitureForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
        ->add('serie', TextType::class)
        ->add('dateMiseEnMarche', DateType::class)
        ->add('modele', EntityType::class, [
            'class' => Modele::class,
            'choice_label' => 'libelle',
            'placeholder' => 'Choisir un modèle',
            'label' => 'Modèle'
        ])
        ->add('prixJour', NumberType::class);
    }

    public function getName(){
        return 'voiture';
    }

}
