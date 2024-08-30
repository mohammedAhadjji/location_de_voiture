<?php

namespace App\Form;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\SittingGenerale;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;

class SendMailType extends AbstractType
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
        ->add('name', TextType::class, [
            'label' => 'Name',
            'attr' => [
                'class' => 'form-control',
                'required' => true,
            ],
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => [
                'class' => 'form-control',
                'required' => true,
            ],
        ])
        ->add('phone', TextType::class, [
            'label' => 'Phone',
            'attr' => [
                'class' => 'form-control',
            ],
            'required' => false,
        ])
        ->add('message', TextareaType::class, [
            'label' => 'Message',
            'attr' => [
                'class' => 'form-control h-100',
                'rows' => 10,
                'cols' => 30,
                'required' => true,
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Send Message',
            'attr' => [
                'class' => 'btn btn-success',
                'style' => 'margin:15px 0;',
            ],
        ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
