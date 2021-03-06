<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace IncubatorLLC\PriorNotify\Test\Unit\Controller;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Upward\Controller as UpwardController;
use PHPUnit\Framework\MockObject\MockObject;
use IncubatorLLC\PriorNotify\Controller\UpwardControllerFactory;

class UpwardControllerFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ScopeConfigInterface|MockObject
     */
    private $config;

    /**
     * @var ObjectManagerInterface|MockObject
     */
    private $objectManager;

    /**
     * @var UpwardControllerFactory
     */
    private $upwardControllerFactory;

    protected function setUp() : void
    {
        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->objectManager = $this->createMock(ObjectManagerInterface::class);
        $this->config = $this->createMock(ScopeConfigInterface::class);
        $this->upwardControllerFactory = $objectManagerHelper->getObject(UpwardControllerFactory::class, [
            'objectManager' => $this->objectManager,
            'config' => $this->config
        ]);
    }

    public function testCreateWillReturn()
    {
        $request = $this->createMock(RequestInterface::class);
        $upwardControllerMock = $this->createMock(UpwardController::class);
        $upwardConfig = 'upward/config/path';
        $this->config->expects($this->once())
            ->method('getValue')
            ->with(UpwardControllerFactory::UPWARD_CONFIG_PATH, 'default')
            ->willReturn($upwardConfig);
        $this->objectManager->expects($this->once())
            ->method('create')
            ->with(UpwardController::class, compact('request', 'upwardConfig'))
            ->willReturn($upwardControllerMock);

        $this->assertSame($upwardControllerMock, $this->upwardControllerFactory->create($request));
    }

    public function testCreateWillThrow()
    {
        $requestMock = $this->createMock(RequestInterface::class);
        $this->config->expects($this->once())
            ->method('getValue')
            ->with(UpwardControllerFactory::UPWARD_CONFIG_PATH, 'default')
            ->willReturn(null);
        $this->objectManager->expects($this->never())->method('create');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Path to UPWARD configuration file not set.');
        $this->upwardControllerFactory->create($requestMock);
    }
}
