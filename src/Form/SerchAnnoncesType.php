<?php

namespace App\Form;

use App\Entity\Type;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SerchAnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('mots', SearchType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Entrez un ou plusieurs mots-clés',
                'style' => 'display: inline-block'
            ],
            'required' => false
        ])
        ->add('type', EntityType::class, [
            'class' => Type::class,
            'attr' => [
                'class' => 'form-control',
                'style' => 'display: inline-block',
                'Placeholder'=>'Entrez un type'
            ],
            'required' => false
        ]);
        
        /*->add('Rechercher', SubmitType::class, [
            'attr' => [
                'class' => 'btn primary',
                'style' => 'display: inline-block;margin:10px'
            ]
            ]);
        */
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
