<?php

namespace App\Controller\Admin;

use App\Entity\ImagesVoiture;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImagesVoitureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImagesVoiture::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            ///IdField::new('id')->onlyOnIndex(),
            ImageField::new('name','images')->setUploadDir('/public/uploads/attachments')->setBasePath('/uploads/attachments'),
          
            //ImageField::new('name')->setUploadDir('/public/uploads/attachments/')->setBasePath('/uploads/attachments'),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        //dd($actions);
        $actions->add(Crud::PAGE_INDEX,Action::DETAIL);
        return $actions;
    }
    
}
