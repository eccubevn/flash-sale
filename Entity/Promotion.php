<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\ORM\Mapping as ORM;
use Plugin\FlashSale\Repository\PromotionRepository;

/**
 * @ORM\Table("flg_flash_sale_promotion")
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\DiscriminatorMap({"abstract" = "Promotion", "amount" = "AmountPromotion"})
 */
class Promotion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length="32", nullable=false)
     */
    protected $operator;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length="32", nullable=false)
     */
    protected $attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    protected $value;

    /**
     * @var Rule
     *
     * @ORM\OneToOne(targetEntity=Rule::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
     * })
     */
    protected $Rule;
}
