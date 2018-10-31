<?php
namespace Plugin\FlashSale\Factory;

use Plugin\FlashSale\Entity\Rule as Rule;
use Plugin\FlashSale\Entity\RuleInterface;

class RuleFactory
{
    /**
     * Create rule
     *
     * @param array $data
     * @return RuleInterface
     */
    public function create(array $data = [])
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('$data[type] must be required');
        }

        switch ($data['type']) {
            case Rule\ProductClassRule::TYPE:
                $Rule = new Rule\ProductClassRule();
                break;
            case Rule\CartRule::TYPE:
                $Rule = new Rule\CartRule();
                break;
            default:
                throw new \InvalidArgumentException($data['type'].' unsupported');
        }
        if (isset($data['operator'])) {
            $Rule->setOperator($data['operator']);
        }

        return $Rule;
    }
}
