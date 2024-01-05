<?php

namespace App\Controller\Admin;

use App\Entity\Annonces;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnnoncesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Annonces::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            HiddenField::new('id'),
            TextField::new('title'),
            TextEditorField::new('descriptions'),
             MoneyField::new('prix_locat')->setCurrency(currencyCode:'MAD'),
             AssociationField::new('voiture', 'Voiture'),
             //AssociationField::new('voiture', 'Voiture')->setCrudController(VoitureCrudController::class)
             //AssociationField::new('voiture', 'Voiture')->autocomplete(),


             
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        //dd($actions);
        $actions->add(Crud::PAGE_INDEX,Action::DETAIL);
        return $actions;
    }
    
}
