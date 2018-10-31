<?php
namespace Plugin\FlashSale\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
use Eccube\Entity\Cart;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Service\Operator;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;
use Plugin\FlashSale\Entity\Promotion\CartTotalAmountPromotion;

/**
 * @ORM\Entity
 */
class CartRule extends Rule
{
    const TYPE = 'rule_cart';

    /**
     * @var array
     */
    protected $cached;


    /**
     * @var DiscriminatorManager
     */
    protected $discriminatorManager;

    /**
     * @param DiscriminatorManager $discriminatorManager
     *
     * @return $this
     * @required
     */
    public function setDiscriminatorManager(DiscriminatorManager $discriminatorManager)
    {
        $this->discriminatorManager = $discriminatorManager;
        $this->discriminator = $discriminatorManager->get(static::TYPE);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getOperatorTypes(): array
    {
        return [
            Operator\InOperator::TYPE,
            Operator\AllOperator::TYPE,
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
            CartTotalCondition::TYPE,
        ];
    }

    /**
     * Todo: implement late
     *
     * {@inheritdoc} createQueryBuilder
     */
    public function createQueryBuilder(QueryBuilder $qb, Operator\OperatorInterface $operatorRule): QueryBuilder
    {
        if (!in_array($operatorRule->getType(), $this->getOperatorTypes())) {
            return $qb;
        }

        return $qb;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getPromotionTypes(): array
    {
        return [
            CartTotalPercentPromotion::TYPE,
            CartTotalAmountPromotion::TYPE,
        ];
    }

    /**
     * Check a product class is matching condition
     *
     * @param Order $Order
     *
     * @return bool
     */
    public function match($Order): bool
    {
        if (!$Order instanceof Order) {
            return false;
        }

        if (isset($this->cached[__METHOD__.$Order->getId()])) {
            return $this->cached[__METHOD__.$Order->getId()];
        }

        $this->cached[__METHOD__.$Order->getId()] = $this->getOperatorFactory()
            ->createByType($this->getOperator())->match($this->getConditions(), $Order);

        return $this->cached[__METHOD__.$Order->getId()];
    }

    /**
     * {@inheritdoc}
     *
     * @param $object
     *
     * @return \Eccube\Entity\ItemInterface[]
     */
    public function getDiscountItems($object): array
    {
        if ($object instanceof Order) {
            return $this->getDiscountItemsFromOrder($object);
        } elseif ($object instanceof Cart) {
            return $this->getDiscountItemsFromCart($object);
        }

        return [];
    }

    /**
     * Get discount items from cart
     *
     * @param Cart $Cart
     *
     * @return array
     */
    protected function getDiscountItemsFromCart(Cart $Cart): array
    {
        if (isset($this->cached[__METHOD__.$Cart->getId()])) {
            return $this->cached[__METHOD__.$Cart->getId()];
        }

        $Order = new Order();
        $Order->setSubtotal($Cart->getTotal());

        $this->cached[__METHOD__.$Cart->getId()] = $this->getDiscountItemsFromOrder($Order);

        return $this->cached[__METHOD__.$Cart->getId()];
    }

    /**
     * Get discount items from order
     *
     * @param Order $Order
     *
     * @return array
     */
    public function getDiscountItemsFromOrder(Order $Order): array
    {
        if (!$this->match($Order)) {
            return [];
        }

        if (isset($this->cached[__METHOD__.$Order->getId()])) {
            return $this->cached[__METHOD__.$Order->getId()];
        }

        $this->cached[__METHOD__.$Order->getId()] = $this->getPromotion()->getDiscountItems($Order);

        return $this->cached[__METHOD__.$Order->getId()];
    }
}
