<?php

namespace Plugin\FlashSale\Controller\Admin;

use Eccube\Controller\AbstractController;
use Eccube\Form\Type\Admin\NewsType;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Util\CacheUtil;
use Knp\Component\Pager\Paginator;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Form\Type\Admin\FlashSaleType;
use Plugin\FlashSale\Repository\ConfigRepository;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FlashSaleController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /** @var FlashSaleRepository */
    protected $flashSaleRepository;

    /** @var PageMaxRepository */
    protected $pageMaxRepository;

    /**
     * FlashSaleController constructor.
     *
     * @param ConfigRepository $configRepository
     * @param FlashSaleRepository $flashSaleRepository
     */
    public function __construct(ConfigRepository $configRepository, FlashSaleRepository $flashSaleRepository, PageMaxRepository $pageMaxRepository)
    {
        $this->configRepository = $configRepository;
        $this->flashSaleRepository = $flashSaleRepository;
        $this->pageMaxRepository = $pageMaxRepository;
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
            'pagination' => $pagination
        ];
    }

    /**
     * 新着情報を登録・編集する。
     *
     * @Route("/%eccube_admin_route%/flash_sale/new", name="flash_sale_admin_new")
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
            if (!$FlashSale->getUrl()) {
                $FlashSale->setLinkMethod(false);
            }
            $this->flashSaleRepository->save($FlashSale);

            $this->addSuccess('admin.common.save_complete', 'admin');

            // キャッシュの削除
            $cacheUtil->clearDoctrineCache();

            return $this->redirectToRoute('admin_content_news_edit', ['id' => $FlashSale->getId()]);
        }

        return [
            'form' => $form->createView(),
            'FlashSale' => $FlashSale,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/flash_sale/{id}/delete", requirements={"id" = "\d+"}, name="flash_sale_admin_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param FlashSale $FlashSale
     * @param CacheUtil $cacheUtil
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
