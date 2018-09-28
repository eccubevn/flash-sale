<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\ORM\Mapping as ORM;
use Plugin\FlashSale\Entity\ProductClassRule;
use Plugin\FlashSale\Repository\RuleRepository;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

/**
 * @ORM\Table("plg_flash_sale_rule")
 * @ORM\Entity(repositoryClass=RuleRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\DiscriminatorMap({Rule::TYPE="Rule", ProductClassRule::TYPE="ProductClassRule"})
 */
class Rule
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
     * @var DoctrineCollection
     *
     * @ORM\OneToMany(targetEntity=Condition::class, mappedBy="Rule", indexBy="id")
     */
    protected $Conditions;

    /**
     * @var Promotion
     *
     * @ORM\OneToOne(targetEntity=Promotion::class, mappedBy="Rule")
     */
    protected $Promotion;

    /**
     * Get $id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set $id
     *
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get Conditions
     *
     * @return DoctrineCollection
     */
    public function getConditions()
    {
        return $this->Conditions;
    }

    /**
     * Set Conditions
     *
     * @param DoctrineCollection $Conditions
     * @return $this
     */
    public function setConditions(DoctrineCollection $Conditions)
    {
        $this->Conditions = $Conditions;
        return $this;
    }

    /**
     * Get Promotion
     *
     * @return Promotion
     */
    public function getPromotion()
    {
        return $this->Promotion;
    }

    /**
     * Set Promotion
     *
     * @param Promotion $Promotion
     * @return $this
     */
    public function setPromotion(Promotion $Promotion)
    {
        $this->Promotion = $Promotion;
        return $this;
    }

    /**
     * Get FlashSale
     *
     * @return FlashSale
     */
    public function getFlashSale()
    {
        return $this->FlashSale;
    }

    /**
     * Set FlashSale
     *
     * @param $FlashSale
     * @return $this
     */
    public function setFlashSale(FlashSale $FlashSale)
    {
        $this->FlashSale = $FlashSale;
        return $this;
    }

    /**
     * Get operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set operator
     *
     * @param $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * Get data as array
     *
     * @param $data
     * @return array
     */
    public function toArray($data = null)
    {
        if ($data) {
            $result = json_decode($data, true);
        } else {
            $result = [
                'id' => intval($this->getId()),
                'type' => static::TYPE,
                'operator' => $this->getOperator()
            ];
            /** @var Condition $Condition */
            foreach ($this->getConditions() as $Condition) {
                $result['conditions'][] = $Condition->toArray();
            }
            $result['promotion'] = $this->getPromotion()->toArray();
        }

        return $result;
    }
}
