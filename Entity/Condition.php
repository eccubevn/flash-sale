<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\ORM\Mapping as ORM;
use Plugin\FlashSale\Repository\ConditionRepository;

/**
 * @ORM\Table("plg_flash_sale_condition")
 * @ORM\Entity(repositoryClass=ConditionRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\DiscriminatorMap({"abstract" = "Condition", "product_class" = "ProductClassCondition"})
 */
class Condition
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
     * @ORM\Column(name="attribute", type="string", length=32, nullable=false)
     */
    protected $attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="operator", type="string", length=32, nullable=false)
     */
    protected $operator;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", nullable=false)
     */
    protected $value;

    /**
     * @var Rule
     *
     * @ORM\ManyToOne(targetEntity=Rule::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
     * })
     */
    protected $Rule;
}
