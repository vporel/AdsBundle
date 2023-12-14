<?php
namespace AdsBundle\Entity;

use RootBundle\Entity\Entity;
use RootBundle\Entity\Trait\CreatedAtTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Advertisement click
 * @ORM\Entity
 * @ORM\Table(name="ads_clicks")
 * @ORM\HasLifecycleCallbacks
 * @author Vivian NKOUANANG (https://github.com/vporel) <dev.vporel@gmail.com>
 */
class AdClick extends Entity{

    use CreatedAtTrait;

    /**
     * @var Ad
     * @ORM\ManyToOne(targetEntity="Ad", inversedBy="clicks")
     * @ORM\JoinColumn(name="ad_id", referencedColumnName="id")
     */
    private $ad;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userId;
    
    public function getAd(): Ad{
        return $this->ad;
    }

    public function setAd(Ad $ad): self{
        $this->ad = $ad;
        return $this;
    }

    public function getUserId(): ?int{
        return $this->userId;
    }

    public function setUserId(?int $userId): self{
        $this->userId = $userId;
        return $this;
    }
}