<?php

namespace App\Controller\Admin;

use App\Entity\Citation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CitationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Citation::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('author'),
            TextareaField::new('content'),
            DateTimeField::new('createAt')->onlyOnDetail()
        ];
    }

}
