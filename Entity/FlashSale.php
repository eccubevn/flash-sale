<?php

namespace Plugin\FlashSale\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FlashSale
 *
 * @ORM\Table(name="plg_flash_sale_flash_sale")
 * @ORM\Entity(repositoryClass="Plugin\FlashSale\Repository\FlashSaleRepository")
 */
class FlashSale
{
    const
        STATUS_DRAFT        = 0,
        STATUS_ACTIVATED    = 1,
        STATUS_DELETED      = 2;

    public static $statusList = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_ACTIVATED => 'Activated'
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="from_time", type="datetimetz", nullable=true)
     */
    private $from_time;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="to_time", type="datetimetz", nullable=true)
     */
    private $to_time;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint", options={"default":true})
     */
    private $status;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetimetz", nullable=true)
     */
    private $created_at;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="update_at", type="datetimetz", nullable=true)
     */
    private $updated_at;

    /**
     * @var \Eccube\Entity\Member
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * })
     */
    private $created_by;

    /**
     * @var ArrayCollection Rule
     *
     * @ORM\OneToMany(targetEntity=Rule::class, mappedBy="FlashSale", cascade={"persist","remove"})
     */
    private $Rules;

    /**
     * FlashSale constructor.
     */
    public function __construct()
    {
        $this->Rules = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getRules(): ArrayCollection
    {
        return $this->Rules;
    }

    /**
     * @param Rule $rule
     */
    public function addRule(Rule $rule): void
    {
        $this->Rules->add($rule);
    }

    public function removeRule(Rule $rule)
    {
        $this->Rules->removeElement($rule);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime|null
     */
    public function getFromTime()
    {
        return $this->from_time;
    }

    /**
     * @param $from_time
     */
    public function setFromTime($from_time)
    {
        $this->from_time = $from_time;
    }

    /**
     * @return \DateTime|null
     */
    public function getToTime()
    {
        return $this->to_time;
    }

    /**
     * @param $to_time
     */
    public function setToTime($to_time)
    {
        $this->to_time = $to_time;
    }

    /**
     * @return mixed
     */
    public function getStatusText()
    {
        return FlashSale::$statusList[$this->status];
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return \Eccube\Entity\Member
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * @param $created_by
     */
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;
    }
}
