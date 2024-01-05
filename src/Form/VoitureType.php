<?php

namespace App\Form;

use App\Entity\ImagesVoiture;
use App\Entity\Modele;
use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\ImageVoitureType;


use function PHPSTORM_META\type;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('modele',EntityType::class,[
                'class' => Modele::class,
                'label' => 'modele',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('annee',DateType::class,[
                'label' => 'annee',
                'widget' =>'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('type',TextType::class,[
                'label' => 'type',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'attr' =>[
                    'type'=> 'file', 'class'=>'btn'
                    
                ],
                
                'mapped' => false,
                'required' => false
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
