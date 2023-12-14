<?php
namespace AdsBundle\Controller;

use AdsBundle\Entity\Ad;
use AdsBundle\Entity\AdClick;
use AdsBundle\Repository\AdRepository;
use RootBundle\Controller\AbstractApiController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use UserAccountBundle\Entity\User;

/**
 * @author Vivian NKOUANANG (https://github.com/vporel) <dev.vporel@gmail.com>
 */
class AdsController extends AbstractApiController{
    
    public function getAds(AdRepository $repo): array{
        return $repo->findBy(["expiresAt__gt" => new \DateTime()]);
    }

    /**
     * This route is not a part of the api
     * id = Ad's id
     * 
     * Save the ad click in the db
     */
    #[Route("/ads/{id}/open", name:".open", methods: ["GET"], requirements: ["id" => "\d+"])]
    public function open(EntityManagerInterface $em, Ad $ad): Response{
        /** @var User */
        $user = $this->getUser();
        $adClick = new AdClick();
        $adClick->setAd($ad);
        if($user != null) $adClick->setUserId($user->getId());  
        $em->persist($adClick);
        $em->flush();
        return $this->redirect($ad->getUrl());
    }
}