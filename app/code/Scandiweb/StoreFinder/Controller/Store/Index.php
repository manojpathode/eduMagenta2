<?php
/**
 * @category    Scandiweb
 * @package     Scandiweb/StoreFinder
 * @author      Andris Bremanis <info@scandiweb.com>
 * @copyright   Copyright (c) 2018 Scandiweb, Inc (https://scandiweb.com)
 * @license     http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

namespace Scandiweb\StoreFinder\Controller\Store;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Scandiweb\StoreFinder\Api\StoreRepositoryInterface;
use Scandiweb\StoreFinder\Helper\Data as DataHelper;

class Index extends Action
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * Index constructor.
     * @param Context $context
     * @param Registry $registry
     * @param StoreRepositoryInterface $storeRepository
     */
    public function __construct(Context $context, Registry $registry, StoreRepositoryInterface $storeRepository)
    {
        parent::__construct($context);

        $this->registry = $registry;
        $this->storeRepository = $storeRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam(DataHelper::URI_PARAM_STORE_ID, null);
        if ($storeId === null || !is_numeric($storeId)) {
            $this->messageManager->addErrorMessage(__('Could not find the store'));

            return $this->goBack();
        }

        try {
            $store = $this->storeRepository->getById($storeId);
        } catch (NotFoundException $exception) {
            $this->messageManager->addErrorMessage(__('This store does not exist'));

            return $this->goBack();
        }

        $this->registry->register(DataHelper::REGISTRY_KEY_CURRENT_STORE, $store);

        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->getConfig()->getTitle()->set($store->getStoreName());

        return $page;
    }

    /**
     * @return Redirect
     */
    protected function goBack()
    {
        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $redirect->setUrl($this->_redirect->getRefererUrl());
    }
}