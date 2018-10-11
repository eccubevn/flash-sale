<?php
namespace Plugin\FlashSale\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Cart;
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
     *
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
     * @param Cart $Cart
     *
     * @return bool
     */
    public function match($Cart): bool
    {
        if (!$Cart instanceof Cart) {
            return false;
        }

        if (isset($this->cached[__METHOD__ . $Cart->getId()])) {
            return $this->cached[__METHOD__ . $Cart->getId()];
        }

        $this->cached[__METHOD__ . $Cart->getId()] = $this->operatorFactory
            ->createByType($this->getOperator())->match($this->getConditions(), $Cart);

        return $this->cached[__METHOD__ . $Cart->getId()];
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
        if (!$object instanceof Cart) {
            return [];
        }

        if (!$this->match($object)) {
            return [];
        }

        if (isset($this->cached[__METHOD__ . $object->getId()])) {
            return $this->cached[__METHOD__ . $object->getId()];
        }

        $this->cached[__METHOD__ . $object->getId()] = $this->getPromotion()->getDiscountItems($object);

        return $this->cached[__METHOD__ . $object->getId()];
    }
}
