<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Plugin\FlashSale\Repository\PromotionRepository;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;

/**
 * @ORM\Table("plg_flash_sale_promotion")
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\DiscriminatorMap({ProductClassPricePercentPromotion::TYPE=ProductClassPricePercentPromotion::class})
 */
abstract class Promotion extends AbstractEntity
{
    const TYPE = 'promotion';

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
     * @ORM\Column(name="value", type="string", nullable=false)
     */
    protected $value;

    /**
     * @var Rule
     *
     * @ORM\OneToOne(targetEntity=Rule::class, inversedBy="Promotion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
     * })
     */
    protected $Rule;

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
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return Rule
     */
    public function getRule(): Rule
    {
        return $this->Rule;
    }

    /**
     * @param Rule $Rule
     */
    public function setRule(Rule $Rule): void
    {
        $this->Rule = $Rule;
    }

    /**
     * Get data as array
     *
     * @param null $data
     * @return array
     */
    public function rawData($data = null)
    {
        $result = [];
        if ($data) {
            $result = json_decode($data, true);
        } else {
            $result['id'] = $this->getId();
            $result['type'] = static::TYPE;
            $result['value'] = $this->getValue();
        }

        return $result;
    }
}
