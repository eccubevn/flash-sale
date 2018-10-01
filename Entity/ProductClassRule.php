<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\ORM\Mapping as ORM;
use Plugin\FlashSale\Service\Rule\RuleInterface;

/**
 * @ORM\Entity
 */
class ProductClassRule extends Rule implements RuleInterface
{
    const TYPE = 'product_class';
}
