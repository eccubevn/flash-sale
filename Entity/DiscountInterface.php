<?php
namespace Plugin\FlashSale\Entity;

interface DiscountInterface
{
    /**
     * Get rule id
     *
     * @return int
     */
    public function getRuleId();

    /**
     * Set rule id
     *
     * @param $ruleId
     * @return DiscountInterface
     */
    public function setRuleId($ruleId);

    /**
     * Get promotion id
     *
     * @return int
     */
    public function getPromotionId();

    /**
     * Set promotion id
     *
     * @param $promotionId
     * @return DiscountInterface
     */
    public function setPromotionId($promotionId);

    /**
     * Get value
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set value
     *
     * @param $value
     * @return DiscountInterface
     */
    public function setValue($value);
}
