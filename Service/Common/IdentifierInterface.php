<?php
namespace Plugin\FlashSale\Service\Common;

interface IdentifierInterface
{
    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;
}