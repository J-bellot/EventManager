<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EventCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $currentUser = $this->security->getUser();
        $userId = $currentUser ? $currentUser->getId() : null;

        return [
            IdField::new('id')
                ->hideWhenCreating()
                ->hideOnIndex()
                ->hideWhenUpdating(),
            TextField::new('title', 'Nom'),
            TextField::new('place', 'Lieu de l\'évènement'),
            TextareaField::new('description', 'Description'),
            DateTimeField::new('begin_at', 'Date et heure de début'),
            DateTimeField::new('end_at', 'Date et heure de fin'),
            AssociationField::new('creator', 'User')
                ->setFormTypeOption('disabled', true)
                ->hideOnForm() 
                ->setCustomOption('value', $userId) 
        ];
    }
}
