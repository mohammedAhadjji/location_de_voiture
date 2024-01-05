<?php

namespace App\Controller\Admin;

use App\Entity\Season;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SeasonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Season::class;
    }

  
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnindex(),
            TextField::new('name'),
            IntegerField::new('taux') ->setFormTypeOption('attr', ['placeholder' => 'Entrez le taux en pourcentage %']),
            DateTimeField::new('date_debut'),
            DatetimeField::new('date_fin'),
            
        ];
    }
   
}
