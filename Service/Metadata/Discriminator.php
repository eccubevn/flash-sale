<?php
namespace Plugin\FlashSale\Service\Metadata;

class Discriminator implements DiscriminatorInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $description;

    /**
     * Metadata constructor.
     *
     * @param $type
     * @param $name
     * @param $class
     * @param $description
     */
    public function __construct($type, $name, $class, $description)
    {
        $this->type = $type;
        $this->name = $name;
        $this->class = $class;
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
