<?php
namespace Plugin\FlashSale\Tests\Service;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Service\Operator\OperatorInterface;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;
use Plugin\FlashSale\Service\Metadata\Discriminator;

abstract class AbstractOperatorTest extends EccubeTestCase
{
    /**
     * @var OperatorInterface
     */
    protected $operator;

    /**
     * @var DiscriminatorManager
     */
    protected $discriminatorManager;

    public function setUp()
    {
        parent::setUp();
        $this->discriminatorManager = $this->container->get(DiscriminatorManager::class);
    }

    public function testGetName()
    {
        /** @var Discriminator $discriminator */
        $discriminator =  $this->discriminatorManager->get($this->operator->getType());
        $this->assertEquals($discriminator->getName(), $this->operator->getName());
    }

    public function testGetType()
    {
        /** @var Discriminator $discriminator */
        $discriminator =  $this->discriminatorManager->get($this->operator->getType());
        $this->assertEquals($discriminator->getType(), $this->operator->getType());
    }
}