<?php

namespace App\Controller\Admin;

use App\Entity\Location;
use App\Entity\SittingGenerale;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LocationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Location::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('locale'),
            ImageField::new('name','image')->setUploadDir('/public/uploads/attachments')->setBasePath('/uploads/attachments'),
          
        ];
    }
     // Fetch the SittingGenerale entity with ID 1
     private function getSittingGenerale(EntityManagerInterface $entityManager): ?SittingGenerale
     {
         return $entityManager->getRepository(SittingGenerale::class)->find(1);
     }
 
     public function createEntity(string $entityFqcn)
     {
         return new $entityFqcn();
     }
 
     public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
     {
         // Fetch SittingGenerale with ID 1 and associate it with the Brand
         $sittingGenerale = $this->getSittingGenerale($entityManager);
         if ($sittingGenerale && method_exists($entityInstance, 'setSittingGenerale')) {
             $entityInstance->setSittingGenerale($sittingGenerale);
         }
 
         $entityManager->persist($entityInstance);
         $entityManager->flush();
     }
 
     public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
     {
         // Ensure the SittingGenerale association is set when updating
         $sittingGenerale = $this->getSittingGenerale($entityManager);
         if ($sittingGenerale && method_exists($entityInstance, 'setSittingGenerale')) {
             $entityInstance->setSittingGenerale($sittingGenerale);
         }
 
         $entityManager->persist($entityInstance);
         $entityManager->flush();
     }
    
}
