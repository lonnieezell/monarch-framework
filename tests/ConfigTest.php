<?php
declare(strict_types=1);

use Monarch\Config;

beforeEach(function () {
    $this->config = Config::instance();
});

test('get returns null for invalid key', function () {
    $this->expectException(RuntimeException::class);
    $this->config->get('');
});

test('get returns null for non-existent file', function () {
    $this->expectException(RuntimeException::class);
    $this->config->get('nonexistent.file');
});

test('finds nested key', function () {
    $this->config->mock('test', ['key' => ['nested' => 'value']]);
    expect($this->config->get('test.key.nested'))->toBe('value');
});

test('merges default and app configs', function () {
    // Should read both test/_support/config/app.php and test/../../config/test.php
    $config = $this->config->get('test');
    expect($config)->toBe([
        'foo' => 'pub',
        'baz' => 'qux',
    ]);
});

test('get returns default config', function () {
    $this->config->mock('test', ['key' => 'value']);
    expect($this->config->get('test.key'))->toBe('value');
});

test('mock replaces entire file', function () {
    $this->config->mock('test', ['key' => 'value']);
    expect($this->config->get('test'))->toBe(['key' => 'value']);
});
