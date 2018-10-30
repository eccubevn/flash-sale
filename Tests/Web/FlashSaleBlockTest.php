<?php

namespace Plugin\FlashSale\Test\Web;

use Eccube\Entity\ProductClass;
use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Entity\Promotion;
use Plugin\FlashSale\Entity\Rule\ProductClassRule;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Service\Operator\InOperator;

/**
 * Class FlashSaleBlockTest
 * @package Plugin\FlashSale\Test\Web
 */
class FlashSaleBlockTest extends AbstractAdminWebTestCase
{
    /** @var FlashSaleRepository */
    protected $flashSaleRepository;

    /**
     * test up
     */
    public function setUp()
    {
        $this->markTestIncomplete('Cannot mock entity');
        parent::setUp();
        $this->flashSaleRepository = $this->container->get(FlashSaleRepository::class);

        for ($i = 1; $i < 5; $i++) {
            $this->createFlashSaleAndRules($i);
        }
    }

    public function testList()
    {
        $crawler = $this->client->request(
            'GET',
            $this->generateUrl('homepage')
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->expected = 'product name 1';
        $this->actual = $crawler->filter('.ec-shelfRole')->text();
        self::assertContains($this->expected, $this->actual);
    }

    public function createFlashSaleAndRules($i)
    {
        $rules['rules'] = $this->rulesData($i);

        $FlashSale = new FlashSale();
        $FlashSale->setName('SQL-scrip-001');
        $FlashSale->setFromTime((new \DateTime())->modify("-{$i} days"));
        $FlashSale->setToTime((new \DateTime())->modify("+{$i} days"));
        $FlashSale->setStatus(FlashSale::STATUS_ACTIVATED);
        $FlashSale->setCreatedAt(new \DateTime());
        $FlashSale->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($FlashSale);
        $this->entityManager->flush($FlashSale);

        $FlashSale->updateFromArray($rules);
        foreach ($FlashSale->getRules() as $Rule) {
            $Promotion = $Rule->getPromotion();
            if ($Promotion instanceof Promotion) {
                if (isset($Rule->modified)) {
                    $this->entityManager->persist($Promotion);
                } else {
                    $this->entityManager->remove($Promotion);
                }
            }
            foreach ($Rule->getConditions() as $Condition) {
                if (isset($Rule->modified)) {
                    $this->entityManager->persist($Condition);
                } else {
                    $this->entityManager->remove($Condition);
                }
            }

            if (isset($Rule->modified)) {
                $this->entityManager->persist($Rule);
            } else {
                $this->entityManager->remove($Rule);
            }
        }
        $this->entityManager->flush();

        return $FlashSale;
    }

    public function rulesData($i)
    {
        $Product = $this->createProduct('product name'.$i);
        $productClassIds = [];
        /** @var ProductClass $productClass */
        foreach ($Product->getProductClasses() as $productClass) {
            $productClassIds[] = $productClass->getId();
        }

        $rules[] = [
            'id' => '',
            'type' => ProductClassRule::TYPE,
            'operator' => InOperator::TYPE,
            'promotion' => [
                'id' => '',
                'type' => Promotion\ProductClassPricePercentPromotion::TYPE,
                'value' => 30,
            ],
            'conditions' => [
                [
                    'id' => '',
                    'type' => ProductClassIdCondition::TYPE,
                    'operator' => InOperator::TYPE,
                    'value' => implode(',', $productClassIds),
                ],
            ],
        ];

        return $rules;
    }
}
