<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\ORM\Mapping as ORM;
use Plugin\FlashSale\Repository\ConditionRepository;
use Plugin\FlashSale\Entity\ProductClassCondition;

/**
 * @ORM\Table("plg_flash_sale_condition")
 * @ORM\Entity(repositoryClass=ConditionRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\DiscriminatorMap({Condition::TYPE="Condition", ProductClassCondition::TYPE="ProductClassCondition"})
 */
class Condition
{
    const TYPE = 'condition';

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
     * @ORM\ManyToOne(targetEntity=Rule::class, inversedBy="Condition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
     * })
     */
    protected $Rule;

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
     * Get $operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set $operator
     *
     * @param $operator
     * @return $this
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Get $attribute
     *
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Set $attribute
     *
     * @param $attribute
     * @return $this
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    /**
     * Get $value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set $value
     *
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get $Rule
     *
     * @return Rule
     */
    public function getRule()
    {
        return $this->Rule;
    }

    /**
     * Set $Rule
     *
     * @param Rule $Rule
     * @return $this
     */
    public function setRule(Rule $Rule)
    {
        $this->Rule = $Rule;
        return $this;
    }

    /**
     * Get data as array
     *
     * @param null $data
     * @return array
     */
    public function toArray($data = null)
    {
        $result = [];
        if ($data) {
            $result = json_decode($data, true);
        } else {
            $result['id'] = intval($this->getId());
            $result['type'] = static::TYPE;
            $result['attribute'] = $this->getAttribute();
            $result['operator'] = $this->getOperator();
            $result['value'] = $this->getValue();
        }

        return $result;
    }
}
