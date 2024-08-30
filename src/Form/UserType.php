<?php

namespace App\Form;

use App\Entity\SittingGenerale;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        $builder
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('name', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('phone', TextType::class, [
            'label' => 'Téléphone',
            'required' => false,
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('ville', TextType::class, [
            'label' => 'Ville',
            'required' => false,
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('pays', TextType::class, [
            'label' => 'Pays',
            'required' => false,
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'btn mt_10',
                'style' => sprintf(
                    'background-color: %s; color: white; padding: 5px 20px; margin: 10px',
                    $sittingGenerale->getThemeColor()
                ),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
