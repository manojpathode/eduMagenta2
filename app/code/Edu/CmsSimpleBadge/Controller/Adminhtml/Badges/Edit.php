<?php
/**
 * Edu_CmsSimpleBadge add new badge.
 * @category    Edu
 * @package     Edu\CmsSimpleBadge
 * @author      Maxim Dzyuba
 */

namespace Edu\CmsSimpleBadge\Controller\Adminhtml\Badges;

use Edu\CmsSimpleBadge\Controller\Adminhtml\Badges;

class Edit extends Badges
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        //$badgesId = $this->getRequest()->getParam('badge_id');
        $resultPage = $this->resultPageFactory->create();
       /* $resultPage->setActiveMenu('Edu_CmsSimpleBadge::badges')
            ->addBreadcrumb(__('Badges'), __('Badges'))
            ->addBreadcrumb(__('Manage Badges'), __('Manage Badges'));

        if ($badgesId === null) {
            $resultPage->addBreadcrumb(__('New Badges'), __('New Badges'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Badges'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Badges'), __('Edit Badges'));
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Badges'));
        }*/
       var_dump($resultPage);

        return $resultPage;
    }
}