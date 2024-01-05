<?php

namespace App\Form;

use App\Entity\Reservation;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class ,[
                'label' => 'title',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('start',DateType::class,[
                'label' => 'annee',
                'widget' =>'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('end',DateType::class,[
                'label' => 'annee',
                'widget' =>'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description',TextareaType::class,[
                'label' => 'descriptions','required' => false,
                'attr' => [
                    'class' => 'form-control',
                    
                ]
            ])
            ->add('all_day', CheckboxType::class,[
                'label' => 'all_day',
                
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                    ,'style'=> 'margin: auto',
                    
                    
                ]
            ])
            ->add('background_color',ColorType::class,[
                
                
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('border_color',ColorType::class,[
                
                
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('text_color',ColorType::class,[
             
             
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('client')
            ->add('voiture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
