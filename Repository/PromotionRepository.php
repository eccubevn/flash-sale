<?php
namespace Plugin\FlashSale\Repository;

use Eccube\Repository\AbstractRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Plugin\FlashSale\Entity\Promotion;

class PromotionRepository extends AbstractRepository
{
    /**
     * PromotionRepository constructor.
     * @param ManagerRegistry $registry
     * @param string $entityClass
     */
    public function __construct(ManagerRegistry $registry, $entityClass = Promotion::class)
    {
        parent::__construct($registry, $entityClass);
    }
}
