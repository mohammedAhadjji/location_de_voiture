<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\Voiture;
use App\Form\ImagesVoitureType;
use App\Repository\BrandRepository;
use App\Repository\ModeleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class VoitureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Voiture::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            CollectionField::new('images')
            //->setEntryIsComplex(false)
            //->setFormType(ImagesVoitureType::class)
            ->setEntryType(ImagesVoitureType::class)
            ,
            DateField::new('annee'),
            AssociationField::new('brand')
                ->setFormTypeOption('query_builder', function (BrandRepository $repo) {
                    return $repo->createQueryBuilder('b')
                        ->orderBy('b.name', 'ASC');
                })
                ->setFormTypeOption('attr', ['id' => 'brand-select']),
            AssociationField::new('modele')
                ->setFormTypeOption('query_builder', function (ModeleRepository $repo) {
                    return $repo->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC');
                })
                ->setFormTypeOption('attr', ['id' => 'model-select']),
            AssociationField::new('type'),
            AssociationField::new('location'),
            AssociationField::new('reservations')->hideOnForm(),
            AssociationField::new('annonces')->hideOnForm()->hideOnindex()
          
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        //dd($actions);
        $actions->add(Crud::PAGE_INDEX,Action::DETAIL);
        return $actions;
    }

    public function createEntity(string $entityFqcn)
    {
        return new $entityFqcn();
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        //dd($entityInstance->getLocation());
        $location= $entityInstance->getLocation();
        $brand = $entityInstance->getModele()->getBrand();
        $location->addBrand($brand);
        $entityManager->persist($entityInstance);
        //dd($entityInstance->getLocation());
        $entityManager->flush();
    }
    



    
}
