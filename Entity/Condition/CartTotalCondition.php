<?php
namespace Plugin\FlashSale\Entity\Condition;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Entity\Operator;
use Plugin\FlashSale\Factory\OperatorFactory;

/**
 * @ORM\Entity()
 */
class CartTotalCondition extends Condition
{
    const TYPE = 'condition_cart_total';

    /**
     * @var OperatorFactory
     */
    protected $operatorFactory;

    /**
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
     * @param $data
     *
     * @return bool
     */
    public function match($data)
    {
        if ($data instanceof Order) {
            return $this->operatorFactory->create([
                'type' => $this->getOperator()
            ])->match($this->value, $data->getSubtotal());
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
