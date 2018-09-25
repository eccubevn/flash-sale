<?php
namespace Plugin\FlashSale\Repository;

use Eccube\Repository\AbstractRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Plugin\FlashSale\Entity\Rule;

class RuleRepository extends AbstractRepository
{
    /**
     * RuleRepository constructor.
     * @param ManagerRegistry $registry
     * @param string $entityClass
     */
    public function __construct(ManagerRegistry $registry, $entityClass = Rule::class)
    {
        parent::__construct($registry, $entityClass);
    }
}
