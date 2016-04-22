<?php

namespace BackendBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseTest extends WebTestCase {
    protected function setUp() {
        static::bootKernel();
    }

    public function assertIsUuid($v)
    {
        $this->assertTrue((bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $v));
    }
}