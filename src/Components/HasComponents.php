<?php

namespace Monarch\Components;

trait HasComponents
{
    /**
     * Parses the HTML for custom components.
     */
    private function parseComponents(string $html): string
    {
        $paths = config('app.componentPaths', []);
        $paths = array_map(fn ($path) => substr($path, 0, 1) === '/' ? $path : ROOTPATH . $path, $paths);

        $components = ComponentManager::instance()
            ->forComponentDirectories($paths);
        $components->discover();

        return TagParser::instance($components)
            ->parse($html);
    }
}
