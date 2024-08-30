<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('visitor_name', TextType::class, [
                'label' => 'Name *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('visitor_email', EmailType::class, [
                'label' => 'Email Address *',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('visitor_phone', TextType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('visitor_message', TextareaType::class, [
                'label' => 'Message *',
                'attr' => [
                    'class' => 'form-control h-200',
                    'cols' => 30,
                    'rows' => 10,
                ],
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Send Message',
                'attr' => ['class' => 'btn btn-primary mt_10'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}


