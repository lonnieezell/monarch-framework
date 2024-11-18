<?php

declare(strict_types=1);

namespace Monarch;

use Monarch\Concerns\IsSingleton;
use Monarch\Helpers\Arr;
use RuntimeException;

/**
 * Class Config
 *
 * Simple way to retrieve config settings from local config files.
 *
 * @package Myth
 */
class Config
{
    use IsSingleton;

    /**
     * Provides a local cache for config files
     * that we've already read.
     * @var array
     */
    protected $files = [];

    /**
     * Grab a config value from a file at app/config.
     *
     *
     * @return array|mixed|null
     */
    public function get(string $key)
    {
        $keys = explode('.', $key);

        if ($keys === []) {
            throw new RuntimeException('Invalid config key: '. $key);
        }

        $file = array_shift($keys);

        if (! isset($this->files[$file])) {
            $this->files[$file] = $this->readFile($file);
        }

        if (count($keys) === 0) {
            return $this->files[$file] ?? null;
        }

        return Arr::get($this->files[$file], implode('.', $keys));
    }

    /**
     * Allows for mocking of config files during testing.
     */
    public function mock(string $file, array $data)
    {
        $this->files[$file] = $data;
    }

    /**
     * Reads the contents of the config file and returns it.
     * It first checks the src/../config directory and pulls the
     * default contents from there. It then checks the app's
     * config directory and merges the two together, letting the
     * app's config file override any defaults.
     */
    private function readFile(string $file): array
    {
        $defaultPath = MONARCHPATH . "../config/{$file}.php";
        $appPath = APPPATH . "config/{$file}.php";

        // If neither file exists, throw an exception
        if (! file_exists($defaultPath) && ! file_exists($appPath)) {
            throw new RuntimeException('Config file not found: '. $file);
        }

        $default = file_exists($defaultPath) ? include $defaultPath : [];
        $app = file_exists($appPath) ? include $appPath : [];

        return array_merge($default, $app);
    }
}
