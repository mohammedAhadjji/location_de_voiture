<?php

namespace App\Controller\Admin;

use App\Entity\ImagesBlogs;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Form\FormTypeInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ImagesBlogsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImagesBlogs::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('name','images')->setUploadDir('/public/uploads/attachments')->setBasePath('/uploads/attachments'),
          
        ];
    }
    
}
