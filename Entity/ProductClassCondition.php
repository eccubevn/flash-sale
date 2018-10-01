<?php
namespace Plugin\FlashSale\Entity;

use Plugin\FlashSale\Service\Condition\ConditionInterface;
use Plugin\FlashSale\Service\Operator\OperatorInterface;
use Plugin\FlashSale\Service\Common\IdentifierInterface;
use Plugin\FlashSale\Service\Operator\InOperator;
use Plugin\FlashSale\Service\Operator\AllOperator;
use Plugin\FlashSale\Service\Operator\EqualOperator;
use Plugin\FlashSale\Service\Operator\NotEqualOperator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ProductClassCondition extends Condition implements ConditionInterface, IdentifierInterface
{
    const TYPE = 'product_class';

    /**
     * @var OperatorInterface[]
     */
    protected $operators = [];

    public function __construct()
    {
        $this->operators[] = new AllOperator();
        $this->operators[] = new InOperator();
        $this->operators[] = new EqualOperator();
        $this->operators[] = new NotEqualOperator();
    }

    /**
     * {@inheritdoc}
     *
     * @param $data
     * @return bool
     */
    public function isValid($data)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getAttributes()
    {
        return [
            'id'
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getOperators(): array
    {
        return $this->operators;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return 'ProductClass Condition';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
