<?php declare(strict_types=1);
use Monarch\Factory;
test('get returns same instance', function () {
    $instance1 = Factory::get(\stdClass::class);
    $instance2 = Factory::get(\stdClass::class);

    expect($instance2)->toBe($instance1);
});
test('get returns different instances with different args', function () {
    $instance1 = Factory::get(\stdClass::class, 'arg1');
    $instance2 = Factory::get(\stdClass::class, 'arg2');

    $this->assertNotSame($instance1, $instance2);
});
test('set overrides instance', function () {
    $originalInstance = Factory::get(\stdClass::class);
    $newInstance = new \stdClass();

    Factory::set(\stdClass::class, $newInstance);

    $retrievedInstance = Factory::get(\stdClass::class);

    expect($retrievedInstance)->toBe($newInstance);
    $this->assertNotSame($originalInstance, $retrievedInstance);
});
test('reset class removes instance', function () {
    $instance1 = Factory::get(\stdClass::class);

    Factory::resetClass(\stdClass::class);

    $instance2 = Factory::get(\stdClass::class);

    $this->assertNotSame($instance1, $instance2);
});
test('reset all removes all instances', function () {
    $instance1 = Factory::get(\stdClass::class);
    $instance2 = Factory::get(\DateTime::class);

    Factory::resetAll();

    $newInstance1 = Factory::get(\stdClass::class);
    $newInstance2 = Factory::get(\DateTime::class);

    $this->assertNotSame($instance1, $newInstance1);
    $this->assertNotSame($instance2, $newInstance2);
});
