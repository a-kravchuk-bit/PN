<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace IncubatorLLC\PriorNotify\Test\Unit\Magento\Framework\App;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\MockObject\MockObject;
use IncubatorLLC\PriorNotify\Plugin\Magento\Framework\App\AreaList as AreaListPlugin;

class AreaListTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ScopeConfigInterface|MockObject
     */
    private $scopeConfig;

    /**
     * \Magento\Framework\App\AreaList|MockObject
     */
    private $areaListMock;

    /**
     * @var AreaListPlugin
     */
    private $areaListPlugin;

    protected function setUp() : void
    {
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->areaListMock = $this->createMock(\Magento\Framework\App\AreaList::class);

        $this->scopeConfig->expects($this->any())->method('getValue')->willReturn(
            "foo\r\nbar"
        );

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->areaListPlugin = $objectManagerHelper->getObject(
            AreaListPlugin::class,
            ['scopeConfig' => $this->scopeConfig]
        );
    }

    /**
     * @param string $resultParam
     * @param string $frontNameParam
     * @param string $expected
     *
     * @dataProvider afterGetCodeByFrontNameDataProvider
     */
    public function testAfterGetCodeByFrontName(string $resultParam, string $frontNameParam, string $expected)
    {
        $result = $this->areaListPlugin->afterGetCodeByFrontName(
            $this->areaListMock,
            $resultParam,
            $frontNameParam
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * @return []
     */
    public function afterGetCodeByFrontNameDataProvider()
    {
        return [
            'Adminhtml area passes through' => ['adminhtml', '', 'adminhtml'],
            'Frontend area w/o frontname goes to UPWARD' => ['frontend', '', 'pwa'],
            'Frontend area w/o frontname in allow list goes to UPWARD' => ['frontend', 'baz', 'pwa'],
            'Frontend area with frontname in allow list passes through' => ['frontend', 'foo', 'frontend']
        ];
    }
}
