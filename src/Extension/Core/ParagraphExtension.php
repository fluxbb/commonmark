<?php

namespace FluxBB\CommonMark\Extension\Core;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Extension\ExtensionInterface;
use FluxBB\CommonMark\Renderer\RendererAwareInterface;
use FluxBB\CommonMark\Renderer\RendererAwareTrait;
use FluxBB\CommonMark\Markdown;

/**
 * Original source code from Markdown.pl
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class ParagraphExtension implements ExtensionInterface, RendererAwareInterface
{

    use RendererAwareTrait;

    /**
     * @var Markdown
     */
    private $markdown;

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $this->markdown = $markdown;

        $markdown->on('block', array($this, 'buildParagraph'), 120);
    }

    /**
     * @param Text $text
     */
    public function buildParagraph(Text $text)
    {
        $parts = $text
            ->replace('/\A\n+/', '')
            ->replace('/\n+\z/', '')
            //->replace('/\n+$/', '')
            ->split('/\n{2,}/', PREG_SPLIT_NO_EMPTY);

        $parts->apply(function (Text $part) {
            if (!$this->markdown->getHashRegistry()->exists($part)) {
                $this->markdown->emit('inline', array($part));

                // For every line, remove all leading whitespace
                $part->replace('/^([ \t]*)/m', '');

                $part->setString($this->getRenderer()->renderParagraph((string) $part));
            }

            return $part;
        });

        $parts->apply(function (Text $part) {
            if ($this->markdown->getHashRegistry()->exists($part)) {
                $part->setString(trim($this->markdown->getHashRegistry()->get($part)));
            }

            return $part;
        });

        $text->setString($parts->join("\n\n"));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'paragraph';
    }

}
