<?php
namespace Plugin\FlashSale\Entity\Condition;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Cart;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Service\Operator;

/**
 * @ORM\Entity()
 */
class CartTotalCondition extends Condition
{
    const TYPE = 'condition_cart_total';

    /**
     * @var Operator\OperatorFactory
     */
    protected $operatorFactory;

    /**
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
     * {@inheritdoc}
     *
     * @param $data
     *
     * @return bool
     */
    public function match($data)
    {
        if ($data instanceof Cart) {
            return $this->operatorFactory->createByType($this->getOperator())->match($this->value, $data->getTotal());
        }

        if ($data instanceof Order) {
            return $this->operatorFactory->createByType($this->getOperator())->match($this->value, $data->getSubtotal());
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getOperatorTypes(): array
    {
        return [
            Operator\GreaterThanOperator::TYPE,
            Operator\EqualOperator::TYPE,
            Operator\LessThanOperator::TYPE,
        ];
    }
}
