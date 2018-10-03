<?php
namespace Plugin\FlashSale\Service\Rule;

interface RuleInterface
{
    /**
     * Get operator types
     *
     * @return array
     */
    public function getOperatorTypes(): array;

    /**
     * Get condition types
     *
     * @return array
     */
    public function getConditionTypes(): array;

    /**
     * Get promotion types
     *
     * @return array
     */
    public function getPromotionTypes(): array;
}
