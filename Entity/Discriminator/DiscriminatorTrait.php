<?php
namespace Plugin\FlashSale\Entity\Discriminator;

use Plugin\FlashSale\Entity\DiscriminatorInterface;
use Plugin\FlashSale\Repository\DiscriminatorRepository;

trait DiscriminatorTrait
{
    /**
     * @var DiscriminatorInterface
     */
    protected $discriminator;

    /**
     * @var DiscriminatorRepository
     */
    protected $discriminatorRepository;

    /**
     * Get $discriminator
     *
     * @return DiscriminatorInterface
     */
    public function getDiscriminator(): DiscriminatorInterface
    {
        return $this->discriminator;
    }

    /**
     * @param DiscriminatorRepository $discriminatorRepository
     * @return DiscriminatorTrait
     * @required
     */
    public function setDiscriminatorRepository(DiscriminatorRepository $discriminatorRepository): DiscriminatorTrait
    {
        $this->discriminatorRepository = $discriminatorRepository;
        $this->discriminator = $this->discriminatorRepository->find(static::TYPE);
        return $this;
    }
}
