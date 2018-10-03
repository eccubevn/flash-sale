<?php
namespace Plugin\FlashSale\Repository;

use Eccube\Entity\Product;
use Eccube\Repository\AbstractRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Service\Operator\OperatorFactory;

class ConditionRepository extends AbstractRepository
{

    /**
     * @var OperatorFactory
     */
    private $operatorFactory;

    /**
     * PromotionRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry, OperatorFactory $operatorFactory)
    {
        parent::__construct($registry, Condition::class);
        $this->operatorFactory = $operatorFactory;
    }

    /**
     * @return mixed
     */
    public function getProductList()
    {
        /** @var Rule[] $Rules */
        $Rules = $this->getEntityManager()->getRepository('Plugin\FlashSale\Entity\Rule')->getAllRule();
        $arrayProductTmp = [];

        $prodRepository = $this->getEntityManager()->getRepository('Eccube\Entity\Product');
        foreach ($Rules as $rule) {
            $qbItem = $prodRepository->createQueryBuilder('p');
            $qbItem->join('p.ProductClasses', 'pc')
                ->groupBy('p');
            $conditions = $rule->getConditions();
            foreach ($conditions as $condition) {
                if ($condition instanceof ProductClassIdCondition) {
                    $condOperator = $condition->getOperator();
                    $Condition = $this->operatorFactory->createByType($condOperator);
                    $qbItem = $Condition->parseCondition($qbItem, $condition);
                }
            }

            $arrayProductTmp[$rule->getId()]['product'] = $qbItem->getQuery()->getResult();
            $arrayProductTmp[$rule->getId()]['promotion'] = $rule->getPromotion();
        }

        $product = [];
        foreach ($arrayProductTmp as $key => $value) {
            // Todo: still not check attribute + operate
            $promotion = empty($value['promotion']) ? null : $value['promotion']->getValue();

            /** @var Product $Product */
            foreach ($value['product'] as $Product) {
                $product[$Product->getId()]['product'] = $Product;
                if ($promotion) {
                    $product[$Product->getId()]['promotion'][] = $promotion;
                    sort($product[$Product->getId()]['promotion']);
                }
            }
        }

        return $product;
    }
}
