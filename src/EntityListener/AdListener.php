<?php
namespace AdsBundle\EntityListener;

use AdsBundle\Entity\Ad;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsEntityListener(event: Events::postRemove, entity: Ad::class)]
class AdListener{

    public function __construct(private ParameterBagInterface $parameterBag){}

    public function postRemove(Ad $ad){
        //Delete the ad file
        unlink($this->parameterBag->get("public_dir") . Ad::FILES_FOLDER . "/".$ad->getFile());
    }   
}