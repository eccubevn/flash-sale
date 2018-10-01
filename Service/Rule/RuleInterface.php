<?php
namespace Plugin\FlashSale\Service\Rule;

use Plugin\FlashSale\Service\Operator\OperatorInterface;
use Plugin\FlashSale\Service\Common\IdentifierInterface;

interface RuleInterface
{
    /**
     * Get allowed operators
     *
     * @return OperatorInterface[]|IdentifierInterface[]
     */
    public function getOperators(): array;
}
