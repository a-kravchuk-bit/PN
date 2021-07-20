<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Milestone
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace PriorNotify\UpwardConnector\Model\Config\Source;

use PriorNotify\UpwardConnector\Model\Config\AbstractSource;

/**
 * Class Schedule
 * @package PriorNotify\UpwardConnector\Model\Config\Source
 */
class Schedule extends AbstractSource
{
    const DISABLE = '0';
    const CRON_DAILY = 'day';
    const CRON_WEEKLY = 'week';
    const CRON_MONTHLY = 'month';
    const CRON_MINUTE = 'minute';

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::DISABLE => __('Disable'),
            self::CRON_DAILY => __('Daily'),
            self::CRON_WEEKLY => __('Weekly'),
            self::CRON_MONTHLY => __('Monthly'),
            self::CRON_MINUTE => __('Every Minute')
        ];
    }
}
