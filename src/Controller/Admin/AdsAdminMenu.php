<?php

namespace AdsBundle\Controller\Admin;

use AdsBundle\Entity\Ad;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;

/**
 * To be registered in the app admin dashboard
 */
class AdsAdminMenu
{
    public static function getMenu(): array{
        return [
            MenuItem::section("Publicités"),
            MenuItem::linkToCrud("Annonces", "fas fa-ad", Ad::class)->setController(AdsCrudController::class),
        ];
    }
}