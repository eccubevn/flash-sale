<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * @ORM\Table("plg_flash_sale_rule")
 * @ORM\Entity(repositoryClass="Plugin\FlashSale\Repository\RuleRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\DiscriminatorMap({Rule::TYPE="Rule", ProductClassRule::TYPE="ProductClassRule"})
 */
class Rule extends AbstractEntity
{
    const TYPE = 'rule';

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

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity=Condition::class, mappedBy="Rule", cascade={"persist"})
     */
    protected $Conditions;

    /**
     * @var Promotion
     * @ORM\OneToOne(targetEntity=Promotion::class, mappedBy="Rule", cascade={"persist"})
     */
    protected $Promotion;

    /**
     * Rule constructor.
     */
    public function __construct()
    {
        $this->Conditions = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getConditions()
    {
        return $this->Conditions;
    }

    /**
     * @param Condition $condition
     */
    public function addConditions(Condition $condition): void
    {
        $this->Conditions->add($condition);
    }

    /**
     * @param Condition $condition
     * @return bool
     */
    public function removeCondition(Condition $condition)
    {
        return $this->Conditions->removeElement($condition);
    }

    public function getFlashSale()
    {
        return $this->FlashSale;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * @return Promotion
     */
    public function getPromotion()
    {
        return $this->Promotion;
    }

    /**
     * @param Promotion $Promotion
     */
    public function setPromotion(Promotion $Promotion): void
    {
        $this->Promotion = $Promotion;
    }
}
