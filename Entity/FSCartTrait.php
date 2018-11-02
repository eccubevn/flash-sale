<?php
namespace Plugin\FlashSale\Entity;

use Eccube\Annotation as Eccube;

/**
 * @Eccube\EntityExtension("Eccube\Entity\Cart")
 */
trait FSCartTrait
{
    use AbstractShoppingTrait;
}
