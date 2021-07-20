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

use Psr\Log\LoggerInterface;
use Magento\Framework\Event\Observer;

/**
 * Class AfterShipment
 * @package PriorNotify\UpwardConnector\Observer
 */
class AfterShipment extends AfterSave
{
    /**
     * AfterShipment constructor.
     *
     * @param LoggerInterface $logger
     * 
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     *
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $item = $observer->getDataObject();

        $body = $this->generateLiquidTemplate($item);

        $this->logger->debug('AfterShipment execute', $body);
        
        $this->logger->debug('AfterShipment execute');
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

        $this->logger->debug('AfterShipment updateObserver', $body);

        $this->logger->debug('AfterShipment updateObserver');
    }
}
