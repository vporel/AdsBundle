<?php
namespace AdsBundle\Entity;

use AdsBundle\Controller\AdsController;
use RootBundle\Entity\Trait\TimestampsTrait;
use RootBundle\Entity\FileEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use UserAccountBundle\Entity\User;
use Symfony\Component\Serializer\Annotation as Serializer;
use ApiPlatform\Metadata as Api;

/**
 * Advertisement
 * @ORM\Entity
 * @ORM\Table(name="ads")
 * @ORM\HasLifecycleCallbacks
 * @author Vivian NKOUANANG (https://github.com/vporel) <dev.vporel@gmail.com>
 */
#[
    Api\ApiResource(paginationEnabled: false),
    Api\GetCollection(
        controller: AdsController::class."::getAds",
        read: false,
        normalizationContext: ["groups" => ["default", "ad:read:collection", "files_data"]]
    )
]
class Ad extends FileEntity{

    use TimestampsTrait;

    public const FILES_FOLDER = "/uploads/ads-files";
    public const FILES_EXTENSIONS = [".jpg", ".png", ".jpeg", ".mp4"];
    public const DURATIONS = [
        1 => "Une semaine",
        2 => "Deux semaines",
        3 => "Un mois"
    ];

    /**
     * The person/entreprise that created the ad
     * @var string
     * @ORM\Column(type="string")
     */
    private $owner;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    #[Assert\Email()]
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    #[Assert\Url()]
    #[Serializer\Groups(["ad:read:collection"])]
    private $url;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * Visibility time in seconds
     * @var int
     * @ORM\Column(type="integer")
     */
    #[Serializer\Groups(["ad:read:collection"])]
    private $minimumVisibilityTime;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @return string
     */
    public function getOwner(){
        return $this->owner;
    }

    public function setOwner(string $owner): self{
        $this->owner = $owner;
        return $this;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function setEmail(?string $email): self{
        $this->email = $email;
        return $this;
    }

    public function getUrl(): string{
        return $this->url;
    }

    public function setUrl(?string $url): self{
        $this->url = $url;
        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        if(!array_key_exists($duration, self::DURATIONS))
            throw new \InvalidArgumentException("La durée $duration n'est pas reconnue");
        $this->duration = $duration;
        $this->setExpiresAt();
        return $this;
    }

    public function getExpiresAt(): ?\DateTime
    {
        return $this->expiresAt;
    }

    public function hasExpired(): bool{
        return $this->expiresAt < new \DateTime();
    }

    private function setExpiresAt(): self{
        $this->expiresAt = clone($this->getCreatedAt());
        switch($this->duration){
            case 1: $this->expiresAt->add(new \DateInterval("P7D")); break; //7 days = 2 weeks
            case 2: $this->expiresAt->add(new \DateInterval("P14D")); break; //14 days = 2 weeks
            case 3: $this->expiresAt->add(new \DateInterval("P1M")); break; //1 month
        }
        return $this;
    }

    public function getMinimumVisibilityTime(): int
    {
        return $this->minimumVisibilityTime;
    }

    public function setMinimumVisibilityTime(int $minimumVisibilityTime): self
    {
        $this->minimumVisibilityTime = $minimumVisibilityTime;

        return $this;
    }

    public function getFilesFolder(): string
    {
        return self::FILES_FOLDER;
    }
}