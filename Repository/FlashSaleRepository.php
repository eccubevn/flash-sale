<?php

namespace Plugin\FlashSale\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\FlashSale\Entity\FlashSale;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * FlashSaleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FlashSaleRepository extends AbstractRepository
{
    /**
     * FlashSaleRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FlashSale::class);
    }

    /**
     * @param int $id
     * @return null|object
     */
    public function get($id = 1)
    {
        return $this->find($id);
    }

    /**
     * @return bool|mixed
     */
    public function getAvailableFlashSale()
    {
        $qb = $this->createQueryBuilder('fl');
        try {
            $event = $qb
                ->where(':time_now >= fl.from_time AND :time_now < fl.to_time')
                ->setParameter('time_now', new \DateTime())
                ->andWhere('fl.status = :status')->setParameter('status', FlashSale::STATUS_ACTIVATED)
                ->getQuery();

            return $event->getSingleResult();
        } catch (\Exception $exception) {

            return false;
        }
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderAll()
    {
        $qb = $this->createQueryBuilder('fl');
        $qb->where('fl.status <> :status')->setParameter('status', FlashSale::STATUS_DELETED);
        $qb->orderBy('fl.from_time', 'DESC')
            ->addOrderBy('fl.id', 'DESC');

        return $qb;
    }

    public function save($FlashSale)
    {
        $em = $this->getEntityManager();
        $em->persist($FlashSale);
        $em->flush($FlashSale);
    }

    /**
     * @param \Eccube\Entity\AbstractEntity $FlashSale
     */
    public function delete($FlashSale)
    {
        $FlashSale->setStatus(FlashSale::STATUS_DELETED);
        $em = $this->getEntityManager();
        $em->persist($FlashSale);
        $em->flush($FlashSale);
    }
}
