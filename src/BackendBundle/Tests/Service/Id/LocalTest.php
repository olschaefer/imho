<?php
namespace BackendBundle\Tests\Service\Id;

use BackendBundle\Service\Id\Generator\Uuid;
use BackendBundle\Service\Id\Local;
use BackendBundle\Tests\BaseTest;

class LocalTest extends BaseTest {
    public function testLocalIdService() {
        $mock = $this->getMock(Uuid::class);
        $mock->expects($this->any())
             ->method('generate')
             ->willReturn('testId');

        $service = new Local($mock);
        $this->assertEquals('testId', $service->getId());
    }
}