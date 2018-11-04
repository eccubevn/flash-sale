<?php
namespace Plugin\FlashSale\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
use Eccube\Entity\Cart;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Exception\RuleException;
use Plugin\FlashSale\Service\Operator;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;
use Plugin\FlashSale\Entity\Promotion\CartTotalAmountPromotion;
use Plugin\FlashSale\Entity\DiscountInterface;
use Plugin\FlashSale\Entity\Discount;

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
            Operator\OrOperator::TYPE,
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
            throw new RuleException(trans('flash_sale.rule.exception', ['%operator%' => $operatorRule->getType()]));
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
            return $this->cached[__METHOD__.$Order->getId()]; // @codeCoverageIgnore
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
     * @return DiscountInterface
     */
    public function getDiscount($object): DiscountInterface
    {
        if ($object instanceof Order) {
            return $this->getDiscountFromOrder($object);
        } elseif ($object instanceof Cart) {
            return $this->getDiscountFromCart($object);
        }

        $discount = new Discount();
        $discount->setRuleId($this->getId());

        return $discount;
    }

    /**
     * Get discount items from cart
     *
     * @param Cart $Cart
     *
     * @return DiscountInterface
     */
    protected function getDiscountFromCart(Cart $Cart): DiscountInterface
    {
        $Order = new Order();
        $Order->setPropertiesFromArray(['id' => 'C' . $Cart->getId()]);
        $Order->setSubtotal($Cart->getTotal());

        return $this->getDiscountFromOrder($Order);
    }

    /**
     * Get discount items from order
     *
     * @param Order $Order
     * @return DiscountInterface
     */
    public function getDiscountFromOrder(Order $Order): DiscountInterface
    {
        $discount = new Discount();
        $discount->setRuleId($this->getId());

        if (!$this->match($Order)) {
            return $discount;
        }

        if (isset($this->cached[__METHOD__.$Order->getId()])) {
            return $this->cached[__METHOD__.$Order->getId()]; // @codeCoverageIgnore
        }

        $discount = $this->getPromotion()->getDiscount($Order);
        $discount->setRuleId($this->getId());
        $this->cached[__METHOD__ . $Order->getId()] = $discount;

        return $discount;
    }
}
