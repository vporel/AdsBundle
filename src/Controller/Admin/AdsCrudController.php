<?php

namespace AdsBundle\Controller\Admin;

use AdsBundle\Entity\Ad;
use RootBundle\Controller\Admin\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

/**
 * To be registered in the app admin dashboard
 */
class AdsCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Ad::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return array_merge(parent::configureFields($pageName), [
            "file" => $this->newFileField(Ad::FILES_FOLDER, "file"),
            "duration" => ChoiceField::new("duration")->setChoices(array_flip(Ad::DURATIONS)),
            "url" => UrlField::new("url")->formatValue(function(string $url, Ad $ad) use($pageName){
                $text = $ad->getUrl();
                if($pageName == Crud::PAGE_INDEX && strlen($ad->getUrl()) > 30) $text = substr($ad->getUrl(), 0, 30) . "...";
                return '<a href="'.$ad->getUrl().'" target="_blank">'.$text.'</a>';
            })
        ]);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add("owner");
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular("Annonce")->setEntityLabelInPlural("Annonces");
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    } 
}
