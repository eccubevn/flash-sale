<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\ORM\Mapping as ORM;
use Plugin\FlashSale\Service\Rule\RuleInterface;
use Plugin\FlashSale\Service\Common\IdentifierInterface;
use Plugin\FlashSale\Service\Operator as Operator;

/**
 * @ORM\Entity
 */
class ProductClassRule extends Rule implements RuleInterface, IdentifierInterface
{
    const TYPE = 'product_class';

    /**
     * @var Operator\OperatorInterface[]
     */
    protected $operators = [];

    public function __construct()
    {
        parent::__construct();

        $this->operators[] = new Operator\InOperator();
        $this->operators[] = new Operator\AllOperator();
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getOperators() : array
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
        return 'ProductClass Rule';
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
