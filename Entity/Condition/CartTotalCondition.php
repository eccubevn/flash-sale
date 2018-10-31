<?php
namespace Plugin\FlashSale\Entity\Condition;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Cart;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Entity\Operator;

/**
 * @ORM\Entity()
 */
class CartTotalCondition extends Condition
{
    const TYPE = 'condition_cart_total';

    /**
     * {@inheritdoc}
     *
     * @param $data
     *
     * @return bool
     */
    public function match($data)
    {
        if ($data instanceof Order) {
            return $this->operatorFactory
                ->create(['type' => $this->getOperator()])
                ->match($this->value, $data->getSubtotal());
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
