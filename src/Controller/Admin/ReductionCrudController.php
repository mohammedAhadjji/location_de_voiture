<?php

namespace App\Controller\Admin;

use App\Entity\Reduction;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReductionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reduction::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
