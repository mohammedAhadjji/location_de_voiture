<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Voiture;
use App\Form\VoitureType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'title',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('descriptions',TextareaType::class,[
                'label' => 'descriptions',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('prix_locat',IntegerType::class,[
                'label' => 'Prix',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('voiture', EntityType::class, [
                'class' => Voiture::class,
                'label' => 'les voiture',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.marque', 'ASC');
                },
                'choice_label' => function ($voiture) {
                    // Customize how the choice label is displayed
                    return $voiture->getMarque() . ' ' . $voiture->getModele() . ' (' . $voiture->getAnnee()->format('Y-m-d') . ')';
                },
                'attr'=> [
                    'class' =>'form-select'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
