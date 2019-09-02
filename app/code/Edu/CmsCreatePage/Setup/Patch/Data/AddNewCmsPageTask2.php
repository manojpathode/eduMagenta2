<?php

namespace Edu\CmsCreatePage\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Psr\Log\LoggerInterface;
use Zend\Log as log;

/**
 * Class AddNewCmsPageTask2
 * @package Edu\CmsCreatePage\Setup\Patch\Data
 */
class AddNewCmsPageTask2 implements
    DataPatchInterface,
    PatchRevertableInterface
{
    /**
     * @var PageFactory
     * @var PageFactory
     * @var ModuleDataSetupInterface
     */
    protected $pageFactory;
    protected $logger;
    protected $moduleDataSetup;

    /**
     * @param PageFactory $pageFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param LoggerInterface $logger
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        PageFactory $pageFactory,
        LoggerInterface $logger
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->pageFactory = $pageFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function apply()
    {
        $objectManager = ObjectManager::getInstance();
        $directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
        $rootPath = $directory->getRoot();
        $templatePath = $rootPath . "/app/code/Edu/CmsCreatePage/Template/";

        $writer = new log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new log\Logger();
        $logger->addWriter($writer);

        $pagesData[] = [
            'title' => 'task 2',
            'page_layout' => '1column',
            'meta_keywords' => 'Page task2',
            'meta_description' => 'Page description task2',
            'identifier' => 'task2',
            'content_heading' => 'task2 wep page',
            'content' => file_get_contents($templatePath . "page_task2.html"),
            'layout_update_xml' => '',
            'url_key' => 'page_task2',
            'is_active' => 1,
            'stores' => [0],
            'sort_order' => 0
        ];
        $pagesData[] = [
            'title' => 'gade-style',
            'page_layout' => '1column',
            'meta_keywords' => 'Page task2 gade-style',
            'meta_description' => 'Page description task2 gade-style',
            'identifier' => 'task2-gade-style',
            'content_heading' => 'task2 wep page gade-style',
            'content' => file_get_contents($templatePath . "page_task2_gade-style.html"),
            'layout_update_xml' => '',
            'url_key' => 'page_task2_gade-style',
            'is_active' => 1,
            'stores' => [0],
            'sort_order' => 0
        ];

        $this->moduleDataSetup->startSetup();
        foreach ($pagesData as $page) {
            $this->pageFactory->create()->setData($page)->save();
            $logger->info('add page task2, get template = ' . $templatePath . $page['url_key'] . '.html');
        }
        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}