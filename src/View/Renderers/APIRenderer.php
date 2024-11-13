<?php

namespace Monarch\View\Renderers;

use Monarch\HTTP\Request;
use Monarch\View\RendererInterface;
use RuntimeException;

/**
 * The APIRenderer class is responsible for rendering API responses.
 */
class APIRenderer implements RendererInterface
{
    private ?string $content = null;
    private array $data = [];
    private Request $request;

    /**
     * Creates a new HTMLRenderer instance with a Request object set.
     */
    public static function createWithRequest(Request $request): static
    {
        $renderer = new static();
        $renderer->withRequest($request);

        return $renderer;
    }

    /**
     * Generates the output for the given route file.
     * At this point, the control file has already been loaded and executed,
     * and the results of the control can be set with the `withRouteParams` method.
     */
    public function render(string $routeFile): ?string
    {
        if (! $this->request instanceof Request) {
            return null;
        }

        $route = require $routeFile;

        if (! is_object($route)) {
            throw new RuntimeException('Route file must return an object');
        }

        $method = strtolower($this->request->method);

        if (! method_exists($route, $method)) {
            throw new RuntimeException('Invalid request method');
        }

        $content = $route->{$method}($this->data)?->body();

        http_response_code($route->status());
        header('Content-Type: application/json');
        return json_encode($content);
    }

    /**
     * Sets the content and data to be used when rendering the view.
     * This is generated by the control file, if one exists.
     */
    public function withRouteParams(string $content, array $data = []): self
    {
        $this->content = $content;
        $this->data = $data;

        return $this;
    }

    /**
     * Sets the Request object to be used when rendering the view.
     */
    public function withRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }
}