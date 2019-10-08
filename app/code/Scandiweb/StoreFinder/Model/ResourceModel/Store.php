<?php
/**
 * @category    Scandiweb
 * @package     Scandiweb/StoreFinder
 * @author      Andris Bremanis <info@scandiweb.com>
 * @copyright   Copyright (c) 2018 Scandiweb, Inc (https://scandiweb.com)
 * @license     http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

namespace Scandiweb\StoreFinder\Model\ResourceModel;

use InvalidArgumentException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Serialize\SerializerInterface;
use Scandiweb\StoreFinder\Helper\Data as DataHelper;
use Scandiweb\StoreFinder\Model\Event as EventModel;
use Scandiweb\StoreFinder\Model\Store as StoreModel;

class Store extends AbstractDb
{
    /**
     * Store constructor.
     * @param Context $context
     * @param SerializerInterface $serializer
     * @param null $connectionName
     */
    public function __construct(Context $context, SerializerInterface $serializer, $connectionName = null)
    {
        parent::__construct($context, $connectionName);
        $this->serializer = $serializer;
    }

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init(DataHelper::TABLE_STORES, StoreModel::COLUMN_STORE_ID);
    }

    /**
     * Serialize image Json array
     * @param AbstractModel $object
     * @return AbstractDb
     */
    protected function _beforeSave(AbstractModel $object)
    {
        /** @var StoreModel $storeModel */
        $storeModel = &$object;

        try {
            $jsonOptions = $this->serializer->serialize($storeModel->getImages());
        } catch (InvalidArgumentException $exception) {
            $jsonOptions = '{}';
        }
        $object->setData(StoreModel::COLUMN_IMAGE, $jsonOptions);

        return parent::_beforeSave($object);
    }

    /**
     * Unserialize image Json array
     * @param AbstractModel $object
     * @return AbstractDb
     */
    protected function _afterLoad(AbstractModel $object)
    {
        /** @var StoreModel $storeModel */
        $storeModel = &$object;

        try {
            $jsonOptions = $this->serializer->unserialize(
                $storeModel->getData(StoreModel::COLUMN_IMAGE)
            );
            if (!is_array($jsonOptions)) {
                $jsonOptions = [];
            }
        } catch (InvalidArgumentException $exception) {
            $jsonOptions = [];
        }
        $storeModel->setImages($jsonOptions);

        $storeModel->setEventIds($this->getBindings($storeModel->getStoreId()));

        return parent::_afterLoad($object);
    }

    /**
     * @param int $storeId
     * @return array
     */
    protected function getBindings($storeId)
    {
        if ($storeId === null) {
            return [];
        }

        $connection = $this->getConnection();
        $bindTableName = $connection->getTableName(DataHelper::TABLE_STORE_EVENT_BIND);

        $bindSelect = $connection->select()
            ->from($bindTableName, EventModel::COLUMN_EVENT_ID)
            ->where(StoreModel::COLUMN_STORE_ID . ' = ?', $storeId);

        return $connection->fetchCol($bindSelect);
    }
}