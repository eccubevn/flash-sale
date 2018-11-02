<?php
namespace Plugin\FlashSale\Service\Operator;

class NotInOperator implements OperatorInterface
{
    const TYPE = 'operator_not_in';

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * {@inheritdoc}
     *
     * @param $condition
     * @param $data
     * @return bool
     */
    public function match($condition, $data)
    {
        if (!is_array($condition)) {
            $condition = explode(',', $condition);
        }

        if (is_array($data)) {
            return !array_intersect($condition, $data);
        }

        return !in_array($data, $condition);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return trans('flash_sale.admin.form.rule.operator.is_not_equal_to');
    }
}
