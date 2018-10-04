<?php
namespace Plugin\FlashSale\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Service\Rule\RuleInterface;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;

/**
 * @ORM\Entity
 */
class ProductClassRule extends Rule implements RuleInterface
{
    const TYPE = 'rule_product_class';

    /**
     * @var Operator\OperatorFactory
     */
    protected $operatorFactory;

    /**
     * @var DiscriminatorManager
     */
    protected $discriminatorManager;


    /**
     * Set $operatorFactory
     *
     * @param Operator\OperatorFactory $operatorFactory
     * @return $this
     * @required
     */
    public function setOperatorFactory(Operator\OperatorFactory $operatorFactory)
    {
        $this->operatorFactory = $operatorFactory;
        return $this;
    }

    /**
     * @param DiscriminatorManager $discriminatorManager
     * @return $this
     * @required
     */
    public function setDiscriminatorManager(DiscriminatorManager $discriminatorManager)
    {
        $this->discriminatorManager =  $discriminatorManager;
        $this->discriminator = $discriminatorManager->get(static::TYPE);
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getOperatorTypes() : array
    {
        return [
            Operator\InOperator::TYPE,
            Operator\AllOperator::TYPE
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getConditionTypes(): array
    {
        return [
            ProductClassIdCondition::TYPE
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getPromotionTypes(): array
    {
        return [
            ProductClassPricePercentPromotion::TYPE
        ];
    }

    /**
     * Check a product class is matching condition
     *
     * @param ProductClass $ProductClass
     *
     * @return bool
     */
    public function match($ProductClass): bool
    {
        if (!$ProductClass instanceof ProductClass) {
            throw new \InvalidArgumentException(sprintf('$ProductClass must be %s object', ProductClass::class));
        }

        return $this->operatorFactory->createByType($this->getOperator())->match($this->getConditions(), $ProductClass);
    }

    /**
     * {@inheritdoc}
     *
     * @param $object
     * @return \Eccube\Entity\ItemInterface[]
     */
    public function getDiscountItems($ProductClass): array
    {
        if (!$ProductClass instanceof ProductClass) {
            throw new \InvalidArgumentException(sprintf('$ProductClass must be %s object', ProductClass::class));
        }

        return $this->getPromotion()->getDiscountItems($ProductClass);
    }
}
