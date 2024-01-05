<?php

namespace App\Form;

use App\Entity\SittingGenerale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SittingGeneraleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('logo_img')
            ->add('favicon_img')
            ->add('theme_color')
            ->add('footer_adrss')
            ->add('footer_mail')
            ->add('footer_phone')
            ->add('custome_listing_option')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SittingGenerale::class,
        ]);
    }
}
