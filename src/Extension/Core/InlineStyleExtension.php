<?php

namespace FluxBB\Markdown\Extension\Core;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Event\EmitterAwareInterface;
use FluxBB\Markdown\Event\EmitterAwareTrait;
use FluxBB\Markdown\Extension\ExtensionInterface;
use FluxBB\Markdown\Renderer\RendererAwareInterface;
use FluxBB\Markdown\Renderer\RendererAwareTrait;
use FluxBB\Markdown\Markdown;

/**
 * Original source code from Markdown.pl
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class InlineStyleExtension implements ExtensionInterface, RendererAwareInterface, EmitterAwareInterface
{

    use RendererAwareTrait;
    use EmitterAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $markdown->on('inline', array($this, 'processBold'), 70);
        $markdown->on('inline', array($this, 'processItalic'), 71);
    }

    /**
     * @param Text $text
     */
    public function processBold(Text $text)
    {
        if (!$text->contains('**') && !$text->contains('__')) {
            return;
        }

        // Stars
        $text->replace(
            '{ (\*\*) (?![\s*]) (.+?) (?<!\s) \1 }sx',
            function (Text $w, Text $a, Text $target) {
                return $this->getRenderer()->renderBoldText($target);
            }
        );

        // Underscores
        $text->replace(
            '{ (?<![A-Za-z0-9]) (__) (?![\s_]) (.+?) (?<!\s) \1 (?![A-Za-z0-9]) }sx',
            function (Text $w, Text $a, Text $target) {
                return $this->getRenderer()->renderBoldText($target);
            }
        );
    }

    /**
     * @param Text $text
     */
    public function processItalic(Text $text)
    {
        if (!$text->contains('*') && !$text->contains('_')) {
            return;
        }

        // Stars
        $text->replace(
            '{ (\*) (?![\s*]) (.+?) (?<!\s) \1 }sx',
            function (Text $w, Text $a, Text $target) {
                return $this->getRenderer()->renderItalicText($target);
            }
        );

        // Underscores
        $text->replace(
            '{ (?<![A-Za-z0-9]) (_) (?![\s_]) (.+?) (?<!\s) \1 (?![A-Za-z0-9]) }sx',
            function (Text $w, Text $a, Text $target) {
                return $this->getRenderer()->renderItalicText($target);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'inlineStyle';
    }

}
