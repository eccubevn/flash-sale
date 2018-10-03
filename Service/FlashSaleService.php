<?php
namespace Plugin\FlashSale\Service;

use Plugin\FlashSale\Service\Common\IdentifierInterface;
use Plugin\FlashSale\Service\Condition\ConditionInterface;
use Plugin\FlashSale\Service\Rule\RuleInterface;
use Plugin\FlashSale\Service\Promotion\PromotionInterface;
use Plugin\FlashSale\Entity\ProductClassRule;
use Plugin\FlashSale\Entity\ProductClassCondition;
use Plugin\FlashSale\Entity\AmountPromotion;


class FlashSaleService
{
    /**
     * @var PromotionInterface[]|IdentifierInterface[]
     */
    protected $promotionTypes = [];

    /**
     * @var ConditionInterface[]|IdentifierInterface[]
     */
    protected $conditionTypes = [];

    /**
     * @var RuleInterface[]|IdentifierInterface[]
     */
    protected $ruleTypes = [];

    /**
     * FlashSaleService constructor.
     */
    public function __construct()
    {
        $this->ruleTypes[] = new ProductClassRule();
        $this->conditionTypes[] = new ProductClassCondition();
        $this->promotionTypes[] = new AmountPromotion();
    }

    /**
     * Get mapp of rule
     *
     * @return array
     */
    public function getMetadata()
    {
        $map = [
            'rule' => [
                'types' => []
            ],
            'condition' => [
                'types' => [

                ]
            ] ,
            'promotion' => [
                'types' => [

                ]
            ]
        ];
        foreach ($this->ruleTypes as $ruleType) {
            $map['rule']['types'][$ruleType->getType()] = [
                'label' => $ruleType->getName(),
                'operators' => []
            ];
            foreach ($ruleType->getOperators() as $operator) {
                $map['rule']['types'][$ruleType->getType()]['operators'][$operator->getType()] = $operator->getName();
            }
        }

        foreach ($this->conditionTypes as $conditionType) {
            $map['condition']['types'][$conditionType->getType()] = [
                'label' => $conditionType->getName(),
                'operators' => [],
                'attributes' => []
            ];
            foreach ($conditionType->getOperators() as $operator) {
                $map['condition']['types'][$conditionType->getType()]['operators'][$operator->getType()] = $operator->getName();
            }
            foreach ($conditionType->getAttributes() as $attribute) {
                $map['condition']['types'][$conditionType->getType()]['attributes'][$attribute] = $attribute;
            }
        }

        foreach ($this->promotionTypes as $promotionType) {
            $map['promotion']['types'][$promotionType->getType()] = [
                'label' => $promotionType->getName(),
                'attributes' => []
            ];
            foreach ($promotionType->getAttributes() as $attribute) {
                $map['promotion']['types'][$promotionType->getType()]['attributes'][$attribute] = $attribute;
            }
        }

        return $map;
    }
}
