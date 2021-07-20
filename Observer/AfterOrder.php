<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Webhook
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace PriorNotify\UpwardConnector\Observer;

use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\App\ResourceConnection;

/**
 * Class AfterOrder
 * @package PriorNotify\UpwardConnector\Observer
 */
class AfterOrder implements ObserverInterface
{
    const SALES_ORDER = 'sales_order';
    const CUSTOMER_ENTITY = 'customer_entity';
    const QUOTE_ADDRESS = 'quote_address';
    const QUOTE_ITEM = 'quote_item';
    /**
     * CURL client
     *
     * @var LoggerInterface
     */
    protected $logger;


    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * AfterSave constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->resourceConnection = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $this->logger = $logger;
        $this->curl = new Curl();
    }
    
    /**
     * @param Observer $observer
     *
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getEntityId();

        $connection = $this->resourceConnection->getConnection();

		$orderTableName = $connection->getTableName(self::SALES_ORDER);
        $selectOrder = $connection->select()->from($orderTableName)->where('entity_id = ?', $orderId);
        $resultOrder = $connection->fetchRow($selectOrder);

		$customerTableName = $connection->getTableName(self::CUSTOMER_ENTITY);
        $selectCustomer = $connection->select()->from($customerTableName)->where('entity_id = ?', $resultOrder['customer_id']);
        $resultCustomer = $connection->fetchRow($selectCustomer);

        $quoteTableName = $connection->getTableName(self::QUOTE_ADDRESS);
        $selectBillingAddress = $connection->select()->from($quoteTableName)->where('quote_id = ?', $resultOrder['quote_id']);
        $resultBillingAddress = $connection->fetchRow($selectBillingAddress);

        $selectShippingAddress = $connection->select()->from($quoteTableName)->where('quote_id = ?', $resultOrder['quote_id']);
        $resultShippingAddress = $connection->fetchRow($selectShippingAddress);

        $quoteItemTableName = $connection->getTableName(self::QUOTE_ITEM);
        $selectQuoteItemAddress = $connection->select()->from($quoteItemTableName)->where('quote_id = ?', $resultOrder['quote_id']);
        $resultQuoteItemAddress = $connection->fetchAll($selectQuoteItemAddress);
        
        $url = "https://ddeaa3f82ad2.ngrok.io/webhooks/magento_order_paid";

        $body = array(
            'order' => $resultOrder,
            'customer' => $resultCustomer,
            'billing_address' => $resultBillingAddress,
            'shipping_address' => $resultShippingAddress,
            'products' => $resultQuoteItemAddress,
        );

        $this->curl->post($url, ['data' =>json_encode($body)]);
        $response = json_decode($this->curl->getBody(), true);
        $result = $this->curl->getBody();

        if (isset($response['success'])) {
            $this->logger->debug('AfterOrder success');
        }
    }

    /**
     * @param $observer
     *
     * @throws Exception
     */
    protected function updateObserver($observer)
    {
        $item = $observer->getDataObject();

        $body = $this->generateLiquidTemplate($item);

        $this->logger->debug('AfterOrder updateObserver', $body);
    }
}
