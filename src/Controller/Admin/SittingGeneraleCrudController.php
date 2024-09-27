<?php

namespace App\Controller\Admin;

use App\Entity\SittingGenerale;
use Doctrine\ORM\EntityManagerInterface;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use JetBrains\PhpStorm\Immutable;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Vich\UploaderBundle\Form\Type\VichFileType;

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
           
            //HiddenField::new('logo_img_file')->hideOnIndex()->hideOnDetail(),
            ImageField::new('logo_img')->setUploadDir('/public/uploads/attachments')->setBasePath('/uploads/attachments'),
            ImageField::new('faviconImg')->setUploadDir('/public/uploads/attachments')->setBasePath('/uploads/attachments'),
          //TextField::new('favicon_img_file')->setFormType(VichImageType::class)->hideOnIndex(),
            //ImageField::new('favicon_img')->setBasePath('/Uploads/attachments/fav')->onlyOnIndex(),
            ColorField::new('theme_color'),
            TextField::new('footer_adrss'),
            EmailField::new('footer_mail'),
            TelephoneField::new('footer_phone'),
            TextField::new('iframe_video') ->setLabel('URL Video'),
            TextField::new('copyright'),


            TextField::new('facebook'), TextField::new('twiter')->setLabel('twitter'), TextField::new('linkdin')->setLabel('linkedin'), TextField::new('pinterest'),
            TextField::new('youtube'),

           /* Field::new('name_file')
                ->setFormType(VichFileType::class)
                ->setLabel('Upload Video')
                ->onlyOnForms(), 
            TextField::new('video')
                ->onlyOnIndex(), 
                TextField::new('name_file')
                ->setFormType(VichFileType::class)
                TextField::new('name_file')
                ->setFormType(FileUploadType::class)
                ->setCustomOption('basePath', 'uploads/files')
                ->setCustomOption('uploadDir', __DIR__ . '/../../public/uploads/files')
               ->setCustomOption('uploadedFileNamePattern', '[year][month][day]-[slug].[extension]')*/
            
            /*   TextField::new('name_file')
               ->setFormType(VichFileType::class)
               ->setLabel('Upload Video')
               ->onlyOnForms(),
               
           TextField::new('video') // Ce champ stocke le nom du fichier
               ->onlyOnIndex(),

                //->setCustomOption('uploadedFileNamePattern', '[year][month][day]-[slug].[extension]')
    
        */
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        /*dd($actions);
        $actions->disable(action::DELETE)
        ->add(Crud::PAGE_EDIT,Action::DETAIL);
        $actions->disable(Crud::PAGE_INDEX);
        return $actions;*/
        $actions->disable(Action::DELETE) 
        // Désactiver l'action "delete"
            ->add(Crud::PAGE_EDIT, Action::DETAIL); // Ajouter l'action "detail" lors de l'édition
        $actions->disable(Crud::PAGE_INDEX); 
       // $actions->disable(Crud::PAGE_INDEX);// Désactiver l'action "index"
        
        return $actions;
    }
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    if ($entityInstance->getNameFile()) {
        $entityInstance->setUpdatedAt(new \DateTimeImmutable());
    }

    $entityManager->persist($entityInstance);
    $entityManager->flush();
}

    
}
