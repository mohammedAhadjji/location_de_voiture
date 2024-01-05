<?php

namespace App\Form;

use App\Form\VoitureType;
use App\Entity\ImagesVoiture;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImagesVoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       
        ->add('name_file', VichFileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImagesVoiture::class,
            "allow_extra_fields" => true,
            'upload_dir' => '/public/uploads/images_voiture', // Substitua pelo caminho real
            'base_path' => '/public/uploads/images_voiture', // Substitua pelo caminho real
        
            //'upload_dir' => '/public/uploads/attachments', // Substitua pelo caminho real
            //'base_path' => '/public/uploads/attachments', // Substitua pelo caminho real

        ]);
    }
}
