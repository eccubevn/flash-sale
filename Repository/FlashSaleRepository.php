<?php

/*
 * This file is part of the Flash Sale plugin
 *
 * Copyright(c) ECCUBE VN LAB. All Rights Reserved.
 *
 * https://www.facebook.com/groups/eccube.vn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\FlashSale\Repository;

use Doctrine\ORM\NonUniqueResultException;
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
     * @var array
     */
    protected $cached = [];

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
     *
     * @return null|object
     */
    public function get($id = 1)
    {
        return $this->find($id);
    }

    /**
     * @return FlashSale
     */
    public function getAvailableFlashSale()
    {
        if (isset($this->cached[__METHOD__])) {
            return $this->cached[__METHOD__];
        }

        $qb = $this->createQueryBuilder('fl');
        $result = false;
        $qb
            ->where(':time_now >= fl.from_time AND :time_now < fl.to_time')
            ->setParameter('time_now', new \DateTime())
            ->andWhere('fl.status = :status')->setParameter('status', FlashSale::STATUS_ACTIVATED);

        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch (NonUniqueResultException $exception) {
            $result = current($qb->getQuery()->getResult());
        } catch (\Exception $exception) {
            // silence
        }

        $this->cached[__METHOD__] = $result;

        return $result;
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
