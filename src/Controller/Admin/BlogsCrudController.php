<?php

namespace App\Controller\Admin;

use App\Entity\Blogs;
use App\Entity\ImagesBlogs;
use App\Form\ImagesBlogsType;
use phpDocumentor\Reflection\Types\Boolean;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Symfony\Component\Form\FormTypeInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BlogsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Blogs::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            CollectionField::new('images')->setEntryType(ImagesBlogsType::class),
            //CollectionField::new('images'),
            TextEditorField::new('content'),
            AssociationField::new('categorie'),
            BooleanField::new('active')
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
        //dd($entityInstance);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

}
