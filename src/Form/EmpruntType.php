<?php

namespace App\Form;

use App\Entity\Adherent;
use App\Entity\Emprunt;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('livre', EntityType::class, [
                'class'=>Livre::class,
                'choice_label'=> 'titre'
            ])
            ->add('adherent', EntityType::class, [
                'class'=>Adherent::class,
                'choice_label'=>'nom'
            ])
            ->add('date_emprunt')
            ->add('date_fin_prevue')
            ->add('date_retour')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
