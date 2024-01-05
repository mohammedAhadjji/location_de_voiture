<?php

namespace App\Controller\Admin;

use App\Entity\SittingGenerale;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use JetBrains\PhpStorm\Immutable;

class SittingGeneraleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SittingGenerale::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            HiddenField::new('id')->onlyOnIndex(),
           
            HiddenField::new('logo_img_file')->hideOnIndex()->hideOnDetail(),
            ImageField::new('logo_img')->setUploadDir('/public/uploads/attachments')->setBasePath('/uploads/attachments'),
            ImageField::new('faviconImg')->setUploadDir('/public/uploads/attachments')->setBasePath('/uploads/attachments'),
          //TextField::new('favicon_img_file')->setFormType(VichImageType::class)->hideOnIndex(),
            //ImageField::new('favicon_img')->setBasePath('/Uploads/attachments/fav')->onlyOnIndex(),
            ColorField::new('theme_color'),
            TextField::new('footer_adrss'),
            EmailField::new('footer_mail'),
            TelephoneField::new('footer_phone')
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        //dd($actions);
        $actions->disable(action::DELETE)
        ->add(Crud::PAGE_EDIT,Action::DETAIL);
        $actions->disable(Crud::PAGE_INDEX);
        return $actions;
    }
    
}
