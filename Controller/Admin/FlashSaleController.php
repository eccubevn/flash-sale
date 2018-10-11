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

namespace Plugin\FlashSale\Controller\Admin;

use Eccube\Controller\AbstractController;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Util\CacheUtil;
use Knp\Component\Pager\Paginator;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Form\Type\Admin\FlashSaleType;
use Plugin\FlashSale\Repository\ConfigRepository;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Entity\Promotion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Plugin\FlashSale\Service\FlashSaleService;

class FlashSaleController extends AbstractController
{
    /**
     * @var FlashSaleRepository
     */
    protected $flashSaleRepository;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * @var FlashSaleService
     */
    protected $flashSaleService;

    /**
     * FlashSaleController constructor.
     *
     * @param FlashSaleRepository $flashSaleRepository
     * @param PageMaxRepository $pageMaxRepository
     * @param FlashSaleService $flashSaleService
     */
    public function __construct(
        FlashSaleRepository $flashSaleRepository,
        PageMaxRepository $pageMaxRepository,
        FlashSaleService $flashSaleService
    ) {
        $this->flashSaleRepository = $flashSaleRepository;
        $this->pageMaxRepository = $pageMaxRepository;
        $this->flashSaleService = $flashSaleService;
    }

    /**
     * @Route("/%eccube_admin_route%/flash_sale/list", name="flash_sale_admin_list")
     * @Route("/%eccube_admin_route%/flash_sale/list/page/{page_no}", requirements={"page_no" = "\d+"}, name="flash_sale_admin_list_page")
     *
     * @Template("@FlashSale/admin/list.twig")
     */
    public function index(Request $request, $page_no = 1, Paginator $paginator)
    {
        $qb = $this->flashSaleRepository->getQueryBuilderAll();

        $pagination = $paginator->paginate(
            $qb,
            $page_no,
            $this->eccubeConfig->get('eccube_default_page_count')
        );

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * 新着情報を登録・編集する。
     *
     * @Route("/%eccube_admin_route%/flash_sale/new", name="flash_sale_admin_new", methods={"POST", "GET"})
     * @Route("/%eccube_admin_route%/flash_sale/{id}/edit", requirements={"id" = "\d+"}, name="flash_sale_admin_edit")
     * @Template("@FlashSale/admin/edit.twig")
     *
     * @param Request $request
     * @param null $id
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit(Request $request, $id = null, CacheUtil $cacheUtil)
    {
        if ($id) {
            $FlashSale = $this->flashSaleRepository->find($id);
            if (!$FlashSale) {
                throw new NotFoundHttpException();
            }
            $FlashSale->setUpdatedAt(new \DateTime());
        } else {
            $FlashSale = new FlashSale();
            $FlashSale->setCreatedAt(new \DateTime());
            $FlashSale->setUpdatedAt(new \DateTime());
        }

        $builder = $this->formFactory
            ->createBuilder(FlashSaleType::class, $FlashSale);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->entityManager->beginTransaction();
                $this->flashSaleRepository->save($FlashSale);
                $data = $FlashSale->rawData($form->get('rules')->getData());
                $FlashSale->updateFromArray($data);
                foreach ($FlashSale->getRules() as $Rule) {
                    $Promotion = $Rule->getPromotion();
                    if ($Promotion instanceof Promotion) {
                        if (isset($Promotion->modified)) {
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
                if (isset($FlashSale->removed)) {
                    foreach ($FlashSale->removed as $removedEntity) {
                        $this->entityManager->remove($removedEntity);
                    }
                }

                $this->entityManager->flush();
                $this->entityManager->commit();
                $this->addSuccess('admin.common.save_complete', 'admin');

                // キャッシュの削除
                $cacheUtil->clearDoctrineCache();

                return $this->redirectToRoute('flash_sale_admin_edit', ['id' => $FlashSale->getId()]);
            } catch (\Exception $e) {
                $this->entityManager->rollback();
                $this->addError('admin.common.save_error', 'admin');
            }
        }

        return [
            'form' => $form->createView(),
            'FlashSale' => $FlashSale,
            'metadata' => $this->flashSaleService->getMetadata(),
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/flash_sale/{id}/delete", requirements={"id" = "\d+"}, name="flash_sale_admin_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param FlashSale $FlashSale
     * @param CacheUtil $cacheUtil
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, FlashSale $FlashSale, CacheUtil $cacheUtil)
    {
        $this->isTokenValid();

        log_info('新着情報削除開始', [$FlashSale->getId()]);

        try {
            $this->flashSaleRepository->delete($FlashSale);

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('新着情報削除完了', [$FlashSale->getId()]);

            // キャッシュの削除
            $cacheUtil->clearDoctrineCache();
        } catch (\Exception $e) {
            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $FlashSale->getName()]);
            $this->addError($message, 'admin');

            log_error('新着情報削除エラー', [$FlashSale->getId(), $e]);
        }

        return $this->redirectToRoute('flash_sale_admin_list');
    }
}
