<?php
namespace Plugin\FlashSale\Repository;

use Eccube\Repository\AbstractRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Plugin\FlashSale\Entity\FlashSale;
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

    /**
     * @return mixed
     */
    public function getAllRule()
    {
        $now = new \DateTime();
        $qb = $this->createQueryBuilder('r');
        $qb->join('r.FlashSale', 'fs');
        $qb->where($qb->expr()->in('fs.status', FlashSale::STATUS_ACTIVATED))
            ->andWhere('fs.from_time <= :fromDate')
            ->andWhere('fs.to_time >= :toDate')
            ->setParameters(['fromDate' => $now, 'toDate' => $now]);

        return $qb->getQuery()->getResult();
    }
}