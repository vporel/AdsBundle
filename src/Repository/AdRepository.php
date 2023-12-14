<?php

namespace AdsBundle\Repository;

use AdsBundle\Entity\Ad;
use RootBundle\Repository\Repository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ad>
 *
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @author Vivian NKOUANANG (https://github.com/vporel) <dev.vporel@gmail.com>
 */
class AdRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

}
