<?php

namespace FluxBB\CommonMark;

use FluxBB\CommonMark\Common\Collection;
use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Event\EmitterAwareInterface;
use FluxBB\CommonMark\Extension\Core;
use FluxBB\CommonMark\Extension\Gfm;
use FluxBB\CommonMark\Extension\ExtensionInterface;
use FluxBB\CommonMark\Renderer\RendererAwareInterface;
use FluxBB\CommonMark\Renderer\RendererInterface;
use FluxBB\CommonMark\Renderer\XhtmlRenderer;

/**
 * Ciconia - The New Markdown Parser
 *
 * This is just the central point to manage `renderer` and `extensions`.
 *
 * The `Core` extensions are based on Markdown.pl
 * The `Gfm` extensions are based on Github Flavored Markdown
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Parser
{

    const VERSION = '1.0-dev';

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Collection|ExtensionInterface[]
     */
    private $extensions;

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer = null)
    {
        $this->extensions = new Collection();
        $this->renderer = $renderer;

        if (is_null($this->renderer)) {
            $this->setRenderer($this->getDefaultRenderer());
        }

        $this->addExtensions($this->getDefaultExtensions());
    }

    /**
     * @param string $text
     * @param array  $options
     *
     * @return string
     */
    public function render($text, array $options = [])
    {
        $text = new Text($text);
        $markdown = new Markdown($this->renderer, $text, $options);

        $this->registerExtensions($markdown);

        $markdown->emit('initialize', [$text]);
        $markdown->emit('block', [$text]);
        $markdown->emit('finalize', [$text]);

        return (string) $text;
    }

    /**
     * @param \FluxBB\CommonMark\Renderer\RendererInterface $renderer
     *
     * @return Parser
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return \FluxBB\CommonMark\Renderer\RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param ExtensionInterface $extension
     *
     * @return Parser
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions->set($extension->getName(), $extension);

        return $this;
    }

    /**
     * @param ExtensionInterface[] $extensions
     *
     * @return Parser
     */
    public function addExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }

        return $this;
    }

    /**
     * @param string|object $extension
     *
     * @return Parser
     */
    public function removeExtension($extension)
    {
        if ($extension instanceof ExtensionInterface) {
           $extension = $extension->getName();
        }

        $this->extensions->remove($extension);

        return $this;
    }

    /**
     * @param string|object $extension
     *
     * @return boolean
     */
    public function hasExtension($extension)
    {
        if ($extension instanceof ExtensionInterface) {
            $extension = $extension->getName();
        }

        return $this->extensions->exists($extension);
    }

    /**
     * @return RendererInterface
     */
    protected function getDefaultRenderer()
    {
        return new XhtmlRenderer();
    }

    /**
     * @return ExtensionInterface[]
     */
    protected function getDefaultExtensions()
    {
        return [
            new Core\WhitespaceExtension(),
            new Core\HeaderExtension(),
            new Core\ParagraphExtension(),
            new Core\HtmlBlockExtension(),
            new Core\LinkExtension(),
            new Core\HorizontalRuleExtension(),
            new Core\ListExtension(),
            new Core\CodeExtension(),
            new Core\BlockQuoteExtension(),
            new Core\ImageExtension(),
            new Core\InlineStyleExtension(),
            new Core\EscaperExtension(),
            new Gfm\FencedCodeBlockExtension(),
        ];
    }

    /**
     * @param Markdown $markdown
     */
    protected function registerExtensions(Markdown $markdown)
    {
        foreach ($this->extensions as $extension) {
            if ($extension instanceof RendererAwareInterface) {
                $extension->setRenderer($this->renderer);
            }

            if ($extension instanceof EmitterAwareInterface) {
                $extension->setEmitter($markdown);
            }

            $extension->register($markdown);
        }
    }

}
