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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;

class AddWishFormType extends AbstractType
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
        ->add('reservationDate', DateTimeType::class, [
            'widget' => 'single_text',
            'html5' => true, // Utilisez html5 à true pour utiliser le widget datetime-local
            'attr' => [
                'class' => 'form-inline form-control',
                'type' => 'datetime-local',
            ],
            'label' => 'Start', // Ajustez le label en conséquence
            'required' => true, // Changer en false si la date n'est pas obligatoire
        ])
        
        ->add('numberOfDays', IntegerType::class, [
            'attr' => ['class' => 'form-control','type'=>'number']
            // Add any additional options for the IntegerType field if needed
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Réserver', // Change the label to "Réserver"
            'attr' => [
                'class' => 'btn',
                'style' => sprintf(
                    'background-color: %s; color: white; padding: 5px 20px;margin:15px 0',
                    $sittingGenerale->getThemeColor()
                ),
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
