<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("plg_flash_sale_rule")
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\DiscriminatorMap({"abstract"="rule", "product_class"="ProductClassRule"})
 */
class Rule
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
     * @ORM\Column(name="operator", type="string", length=32, nullable=false)
     */
    protected $operator;

    /**
     * @var FlashSale
     *
     * @ORM\ManyToOne(targetEntity=FlashSale::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="flash_sale_id", referencedColumnName="id")
     * })
     */
    protected $FlashSale;
}
