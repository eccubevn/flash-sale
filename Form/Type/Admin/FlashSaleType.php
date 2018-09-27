<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\FlashSale\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class FlashSaleType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /** @var FlashSaleRepository */
    protected $flashSaleRepository;

    /**
     * FlashSaleType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param FlashSaleRepository $flashSaleRepository
     */
    public function __construct(EccubeConfig $eccubeConfig, FlashSaleRepository $flashSaleRepository)
    {
        $this->eccubeConfig = $eccubeConfig;
        $this->flashSaleRepository = $flashSaleRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from_time', DateTimeType::class, [
                'date_widget' => 'choice',
                'input' => 'datetime',
                'format' => 'yyyy-MM-dd hh:mm',
                'years' => range(date('Y'), date('Y') + 3),
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('to_time', DateTimeType::class, [
                'date_widget' => 'choice',
                'input' => 'datetime',
                'format' => 'yyyy-MM-dd hh:mm',
                'years' => range(date('Y'), date('Y') + 3),
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_mtext_len']]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 8,
                ],
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_ltext_len']]),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => false,
                'choices' => array_flip(FlashSale::$statusList),
                'required' => true,
                'expanded' => false,
            ])
            ->add('rule', TextareaType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var FlashSale $FlashSale */
            $FlashSale = $event->getData();
            $qb = $this->flashSaleRepository->createQueryBuilder('fl');
            $qb
                ->select('count(fl.id)')
                ->where(':from_time >= fl.from_time AND :from_time < fl.to_time')
                ->setParameter('from_time', $FlashSale->getFromTime())
                ->andWhere('fl.status <> :status')->setParameter('status', FlashSale::STATUS_DELETED);

            if ($FlashSale->getId()) {
                $qb
                    ->andWhere('fl.id <> :id')
                    ->setParameter('id', $FlashSale->getId());
            }
            $count = $qb->getQuery()
                ->getSingleScalarResult();

            if ($count > 0) {
                $form = $event->getForm();
                $form['from_time']->addError(new FormError(trans('taxrule.text.error.date_not_available')));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FlashSale::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'flash_sale_admin';
    }
}
