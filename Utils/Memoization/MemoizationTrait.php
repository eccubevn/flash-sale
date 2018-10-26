<?php
namespace Plugin\FlashSale\Utils\Memoization;

use Plugin\FlashSale\Utils\Memoization;

trait MemoizationTrait
{
    /**
     * @var Memoization
     */
    protected $memoization;

    /**
     * @param Memoization $memoization
     * @return $this
     * @required
     */
    public function setMemoization(Memoization $memoization)
    {
        $this->memoization = $memoization;
        return $this;
    }
}
