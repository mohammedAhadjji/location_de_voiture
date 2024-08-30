<?php

namespace App\Form;

use App\Entity\ImageBrand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ImagesBrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name_file', FileType::class, [
            'label' => 'Brand Logo (Image file)',
            'mapped' => false, // Disables mapping to the entity
            'required' => false,
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImageBrand::class,
            "allow_extra_fields" => true,
            'upload_dir' => '/public/uploads/images_voiture', // Substitua pelo caminho real
            'base_path' => '/public/uploads/images_voiture',
        ]);
    }
}
