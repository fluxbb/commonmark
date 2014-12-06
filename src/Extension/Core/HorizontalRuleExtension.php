<?php

namespace FluxBB\Markdown\Extension\Core;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Extension\ExtensionInterface;
use FluxBB\Markdown\Renderer\RendererAwareInterface;
use FluxBB\Markdown\Renderer\RendererAwareTrait;
use FluxBB\Markdown\Markdown;

/**
 * Converts horizontal rules
 *
 * Original source code from Markdown.pl
 *
 * > Copyright (c) 2004 John Gruber
 * > <http://daringfireball.net/projects/markdown/>
 *
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class HorizontalRuleExtension implements ExtensionInterface, RendererAwareInterface
{

    use RendererAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $markdown->on('block', array($this, 'processHorizontalRule'), 20);
    }

    /**
     * @param Text  $text
     */
    public function processHorizontalRule(Text $text)
    {
        $marks = array('*', '-', '_');

        foreach ($marks as $mark) {
            $text->replace(
                '/^[ ]{0,2}([ ]?' . preg_quote($mark, '/') . '[ ]?){3,}[ \t]*$/m',
                $this->getRenderer()->renderHorizontalRule() . "\n"
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'hr';
    }

}
