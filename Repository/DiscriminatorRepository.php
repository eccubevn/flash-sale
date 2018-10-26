<?php
namespace Plugin\FlashSale\Repository;

use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Entity\Rule as Rule;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\Promotion as Promotion;
use Plugin\FlashSale\Entity\DiscriminatorInterface;
use Plugin\FlashSale\Entity\Discriminator;

class DiscriminatorRepository
{
    /**
     * @var array
     */
    protected $unitOfWorks = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * DiscriminatorRepository constructor.
     */
    public function __construct()
    {
        $this->data = $this->getDefaultData();
    }

    public function getDefaultData(): array
    {
        return [
            Operator\AllOperator::TYPE => [
                'type' => Operator\AllOperator::TYPE,
                'name' => trans('flash_sale.admin.form.rule.operator.is_all_of'),
                'description' => '',
                'class' => Operator\AllOperator::class
            ],
            Operator\InOperator::TYPE => [
                'type' => Operator\InOperator::TYPE,
                'name' => trans('flash_sale.admin.form.rule.operator.is_one_of'),
                'description' => '',
                'class' => Operator\InOperator::class
            ],
            Operator\EqualOperator::TYPE => [
                'type' => Operator\EqualOperator::TYPE,
                'name' => trans('flash_sale.admin.form.rule.operator.is_equal_to'),
                'description' => '',
                'class' => Operator\EqualOperator::class
            ],
            Operator\NotEqualOperator::TYPE => [
                'type' => Operator\NotEqualOperator::TYPE,
                'name' => trans('flash_sale.admin.form.rule.operator.is_not_equal_to'),
                'description' => '',
                'class' => Operator\NotEqualOperator::class
            ],
            Operator\GreaterThanOperator::TYPE => [
                'type' => Operator\GreaterThanOperator::TYPE,
                'name' => trans('flash_sale.admin.form.rule.operator.is_greater_than_to'),
                'description' => '',
                'class' => Operator\GreaterThanOperator::class
            ],
            Operator\LessThanOperator::TYPE => [
                'type' => Operator\LessThanOperator::TYPE,
                'name' => trans('flash_sale.admin.form.rule.operator.is_less_than_to'),
                'description' => '',
                'class' => Operator\LessThanOperator::class
            ],
            Rule\ProductClassRule::TYPE => [
                'type' => Rule\ProductClassRule::TYPE,
                'name' => trans('flash_sale.admin.form.rule.product_class_rule'),
                'description' => '',
                'class' => Rule\ProductClassRule::class
            ],
            Rule\CartRule::TYPE => [
                'type' => Rule\CartRule::TYPE,
                'name' => trans('flash_sale.admin.form.rule.cart_rule'),
                'description' => '',
                'class' => Rule\CartRule::class
            ],
            Condition\ProductClassIdCondition::TYPE => [
                'type' => Condition\ProductClassIdCondition::TYPE,
                'name' => trans('flash_sale.admin.form.rule.condition.product_class_id_condition'),
                'description' => '',
                'class' => Condition\ProductClassIdCondition::class
            ],
            Condition\ProductCategoryIdCondition::TYPE => [
                'type' => Condition\ProductCategoryIdCondition::TYPE,
                'name' => trans('flash_sale.admin.form.rule.condition.product_category_id_condition'),
                'description' => '',
                'class' => Condition\ProductCategoryIdCondition::class
            ],
            Condition\CartTotalCondition::TYPE => [
                'type' => Condition\CartTotalCondition::TYPE,
                'name' => trans('flash_sale.admin.form.rule.condition.cart_total_condition'),
                'description' => '',
                'class' => Condition\CartTotalCondition::class
            ],
            Promotion\ProductClassPricePercentPromotion::TYPE => [
                'type' => Promotion\ProductClassPricePercentPromotion::TYPE,
                'name' => trans('flash_sale.admin.form.rule.product_class_price_percent_promotion'),
                'description' => '',
                'class' => Promotion\ProductClassPricePercentPromotion::class
            ],
            Promotion\ProductClassPriceAmountPromotion::TYPE => [
                'type' => Promotion\ProductClassPriceAmountPromotion::TYPE,
                'name' => trans('flash_sale.admin.form.rule.product_class_price_amount_promotion'),
                'description' => '',
                'class' => Promotion\ProductClassPriceAmountPromotion::class
            ],
            Promotion\CartTotalAmountPromotion::TYPE => [
                'type' => Promotion\CartTotalAmountPromotion::TYPE,
                'name' => trans('flash_sale.admin.form.rule.cart_total_amount_promotion'),
                'description' => '',
                'class' => Promotion\CartTotalAmountPromotion::class
            ],
            Promotion\CartTotalPercentPromotion::TYPE => [
                'type' => Promotion\CartTotalPercentPromotion::TYPE,
                'name' => trans('flash_sale.admin.form.rule.cart_total_percent_promotion'),
                'description' => '',
                'class' => Promotion\CartTotalPercentPromotion::class
            ],
        ];
    }

    /**
     * Find an Discriminator entity by type
     *
     * @param string $type
     * @return DiscriminatorInterface
     */
    public function find($type): DiscriminatorInterface
    {
        if (isset($this->unitOfWorks[$type])) {
            return $this->unitOfWorks[$type];
        }

        if (!isset($this->data[$type])) {
            throw new \InvalidArgumentException('The discriminator '.$type.' does not exist');
        }

        $this->unitOfWorks[$type] = (new Discriminator())
            ->setType($this->data[$type]['type'])
            ->setName($this->data[$type]['name'])
            ->setClass($this->data[$type]['class'])
            ->setDescription($this->data[$type]['description']);

        return $this->unitOfWorks[$type];
    }
}
