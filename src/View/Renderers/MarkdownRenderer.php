<?php

declare(strict_types=1);

namespace Monarch\View\Renderers;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Monarch\Components\HasComponents;
use Monarch\HTTP\Request;
use Monarch\View\HasLayouts;
use Monarch\View\RendererInterface;
use RuntimeException;

class MarkdownRenderer implements RendererInterface
{
    use HasLayouts;
    use HasComponents;

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
     *
     * NOTE: The route file is the full path to the file.
     */
    public function render(string $routeFile): ?string
    {
        $hasRequest = $this->request instanceof Request;

        $contentHtml = $this->generateView($routeFile);

        if (! $hasRequest) {
            return $contentHtml;
        }

        if (! $this->needsLayout()) {
            return $contentHtml;
        }

        $layoutHtml = $this->renderLayout($routeFile);
        $layoutHtml = $this->parseComponents($layoutHtml);

        return str_replace('<slot></slot>', $contentHtml, (string) $layoutHtml);
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

    /**
     * Renders a single PHP file, returning the generated HTML.
     * If either $content or $data are set, they will be available to the view.
     */
    private function generateView(string $file): string
    {
        if (! file_exists($file)) {
            throw new RuntimeException("View not found: {$file}");
        }

        $environment = new Environment([]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new FrontMatterExtension());

        $converter = new MarkdownConverter($environment);

        $markdown = $converter->convert(file_get_contents($file));

        if ($markdown instanceof RenderedContentWithFrontMatter) {
            $this->addViewMeta($markdown->getFrontMatter());
        }

        $markdown = $markdown->getContent();

        // TODO - Escape the content and data before replacing
        // Do some string replacement with content and data before returning
        if (isset($content)) {
            $markdown = str_replace('{{ content }}', $content, $markdown);
        }

        if (isset($data)) {
            foreach ($data as $key => $value) {
                $markdown = str_replace('{{ '. $key .' }}', $value, $markdown);
            }
        }

        return $markdown;
    }

    /**
     * Given the frontmatter from a Markdown file, adds the appropriate
     * meta tags to the view.
     *
     * @return void
     */
    private function addViewMeta(array $frontMatter): void
    {
        $meta = viewMeta();

        if (isset($frontMatter['title'])) {
            $meta->setTitle($frontMatter['title']);
        }

        if (isset($frontMatter['description'])) {
            $meta->addMeta(['description' => $frontMatter['description']]);
        }

        if (isset($frontMatter['meta'])) {
            foreach ($frontMatter['meta'] as $meta) {
                viewMeta()->addMeta($meta);
            }
        }

        if (isset($frontMatter['styles'])) {
            foreach ($frontMatter['styles'] as $style) {
                viewMeta()->addStyle($style);
            }
        }

        if (isset($frontMatter['scripts'])) {
            foreach ($frontMatter['scripts'] as $script) {
                viewMeta()->addScript($script);
            }
        }
    }
}