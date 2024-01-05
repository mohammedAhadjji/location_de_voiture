<?php

namespace App\Form;

use App\Entity\ImagesVoiture;
use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Form\ImageVoitureType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class VichImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // ...

        $builder->add('imageFile', VichFileType::class);
    }
}