<?php
namespace Plugin\FlashSale\Entity;

use Eccube\Annotation as Eccube;

/**
 * @Eccube\EntityExtension("Eccube\Entity\CartItem")
 */
trait FSCartItemTrait
{
    use AbstractItemTrait;
}
