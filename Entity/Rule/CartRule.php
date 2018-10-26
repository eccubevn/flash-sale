<?php
namespace Plugin\FlashSale\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Cart;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Entity\Operator;
use Plugin\FlashSale\Factory\OperatorFactory;
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;
use Plugin\FlashSale\Entity\Promotion\CartTotalAmountPromotion;

/**
 * @ORM\Entity
 */
class CartRule extends Rule
{
    use \Plugin\FlashSale\Utils\Memoization\MemoizationTrait;

    const TYPE = 'rule_cart';

    /**
     * @var OperatorFactory
     */
    protected $operatorFactory;

    /**
     * Set $operatorFactory
     *
     * @param OperatorFactory $operatorFactory
     *
     * @return $this
     * @required
     */
    public function setOperatorFactory(OperatorFactory $operatorFactory)
    {
        $this->operatorFactory = $operatorFactory;

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

        // @codeCoverageIgnoreStart
        if ($this->memoization->has($Order->getId())) {
            return $this->memoization->get($Order->getId());
        }
        // @codeCoverageIgnoreEnd

        $result = $this->operatorFactory
            ->create(['type' => $this->getOperator()])
            ->match($this->getConditions(), $Order);

        $this->memoization->set($result, $Order->getId());

        return $result;
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
     * @return array
     */
    protected function getDiscountItemsFromCart(Cart $Cart): array
    {
        $Order = new Order();
        $Order->offsetSet('id', 'C' . $Cart->getId());
        $Order->setSubtotal($Cart->getTotal());

        return $this->getDiscountItemsFromOrder($Order);
    }

    /**
     * Get discount items from order
     *
     * @param Order $Order
     * @return array
     */
    public function getDiscountItemsFromOrder(Order $Order): array
    {
        if (!$this->match($Order)) {
            return [];
        }

        // @codeCoverageIgnoreStart
        if ($this->memoization->has($Order->getId())) {
            return $this->memoization->get($Order->getId());
        }
        // @codeCoverageIgnoreEnd

        $result = $this->getPromotion()->getDiscountItems($Order);
        $this->memoization->set($result, $Order->getId());

        return $result;
    }
}
