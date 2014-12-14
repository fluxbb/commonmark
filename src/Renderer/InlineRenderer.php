<?php

namespace FluxBB\Markdown\Renderer;

use FluxBB\Markdown\Common\Tag;
use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\InlineParser;
use FluxBB\Markdown\Node\Emphasis;
use FluxBB\Markdown\Node\StrongEmphasis;

class InlineRenderer implements RendererInterface
{

    /**
     * @var InlineParser
     */
    protected $parser;


    public function __construct(InlineParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @api
     *
     * @param string|Text $content
     * @param array $options
     *
     * @return string
     */
    public function renderParagraph($content, array $options = [])
    {
        return;
    }

    /**
     * @api
     *
     * @param string|Text $content
     * @param array $options
     *
     * @return string
     */
    public function renderHeader($content, array $options = [])
    {
        return;
    }

    /**
     * @api
     *
     * @param string|Text $content
     * @param array $options
     *
     * @return string
     */
    public function renderCodeBlock($content, array $options = [])
    {
        return;
    }

    /**
     * @api
     *
     * @param string|Text $content
     * @param array $options
     *
     * @return string
     */
    public function renderCodeSpan($content, array $options = [])
    {
        $this->parser->addBlob('CODE' . $content);
        return "\0";
    }

    /**
     * @api
     *
     * @param string|Text $content
     * @param array $options
     *
     * @return string
     */
    public function renderLink($content, array $options = [])
    {
        $this->parser->addBlob('LINK' . $content);
        return "\0";
    }

    /**
     * @api
     *
     * @param string|Text $content
     * @param array $options
     *
     * @return string
     */
    public function renderBlockQuote($content, array $options = [])
    {
        return;
    }

    /**
     * @api
     *
     * @param string|Text $content
     * @param array $options
     *
     * @return string
     */
    public function renderList($content, array $options = [])
    {
        return;
    }

    /**
     * @api
     *
     * @param string|Text $content
     * @param array $options
     *
     * @return string
     */
    public function renderListItem($content, array $options = [])
    {
        return;
    }

    /**
     * @api
     *
     * @param array $options
     *
     * @return string
     */
    public function renderHorizontalRule(array $options = [])
    {
        return;
    }

    /**
     * @api
     *
     * @param string $src
     * @param array $options
     *
     * @return string
     */
    public function renderImage($src, array $options = [])
    {
        $this->parser->addBlob('IMAGE' . $src);
        return "\0";
    }

    /**
     * @api
     *
     * @param string|Text $text
     * @param array $options
     *
     * @return string
     */
    public function renderBoldText($text, array $options = [])
    {
        $this->parser->addBlob(new StrongEmphasis($text));
        return "\0";
    }

    /**
     * @api
     *
     * @param string|Text $text
     * @param array $options
     *
     * @return string
     */
    public function renderItalicText($text, array $options = [])
    {
        $this->parser->addBlob(new Emphasis($text));
        return "\0";
    }

    /**
     * @api
     *
     * @param array $options
     *
     * @return string
     */
    public function renderLineBreak(array $options = [])
    {
        $this->parser->addBlob('');
        return "\0";
    }

    /**
     * @param string $tagName
     * @param string $content
     * @param string $tagType
     * @param array $options
     *
     * @return mixed
     */
    public function renderTag($tagName, $content, $tagType = Tag::TYPE_BLOCK, array $options = [])
    {
        return;
    }

}
