<?php
namespace Plugin\FlashSale\Service\Metadata;

interface DiscriminatorInterface
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

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get class
     *
     * @return string
     */
    public function getClass(): string;
}