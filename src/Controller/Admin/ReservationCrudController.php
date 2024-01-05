<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Entity\Users;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    
   public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
            DateTimeField::new('start'),
            DatetimeField::new('end'),
            BooleanField::new('allDay'),
            ColorField::new('background_color'),
            ColorField::new('border_color'),
            ColorField::new('text_color'),
            AssociationField::new('client', 'client'),
            AssociationField::new('voiture', 'Voiture'),
                  
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        //dd($actions);
        $actions->add(Crud::PAGE_INDEX,Action::DETAIL);
        return $actions;
    }

}
