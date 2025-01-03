<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\SittingGenerale;
use App\Form\ImagesBrandType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BrandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Brand::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            CollectionField::new('logos')
                ->setEntryType(ImagesBrandType::class),
            ImageField::new('imgBrand')
                ->setUploadDir('public/uploads/attachments')
                ->setBasePath('/uploads/attachments'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
        return $actions;
    }

    public function createEntity(string $entityFqcn)
    {
        return new $entityFqcn();
    }

   

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $sittingGenerale = $this->getSittingGenerale($entityManager);
        if ($sittingGenerale && method_exists($entityInstance, 'setSittingGenerale')) {
            $entityInstance->setSittingGenerale($sittingGenerale);
        }

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
    private function getSittingGenerale(EntityManagerInterface $entityManager): ?SittingGenerale
    {
        return $entityManager->getRepository(SittingGenerale::class)->find(1);
    }
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
         $sittingGenerale = $this->getSittingGenerale($entityManager);
        if ($sittingGenerale && method_exists($entityInstance, 'setSittingGenerale')) {
            $entityInstance->setSittingGenerale($sittingGenerale);
        }

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
