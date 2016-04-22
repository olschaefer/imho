<?php
namespace BackendBundle\Tests\Service\Id\Generator;

use BackendBundle\Service\Id\Generator\Uuid as UuidGenerator;
use BackendBundle\Tests\BaseTest;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Validator;

class UuidGeneratorTest extends BaseTest {

    public function testGenerator() {
        $generator = new UuidGenerator();
        $id = $generator->generate();

        $this->assertTrue(is_string($id));
        $this->assertNotEmpty($id);

        /** @var Validator $v */
        $v = self::$kernel->getContainer()->get('validator');
        $errorNumber = $v->validateValue($id, new Uuid())->count();
        $this->assertLessThanOrEqual(0, $errorNumber);
    }
}