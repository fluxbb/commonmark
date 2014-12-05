<?php

namespace FluxBB\Markdown\Extension\Textile;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Extension\ExtensionInterface;
use FluxBB\Markdown\Markdown;

/**
 * [Experimental] Comments
 *
 * @since 1.1
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class CommentExtension implements ExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Markdown $markdown)
    {
        $markdown->on('block', array($this, 'processComment'), 50);
    }

    /**
     * @param Text $text
     */
    public function processComment(Text $text)
    {
        $text->replace('/^###\.[ \t]*(.+?)\n{2,}/m', "\n\n");
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'comment';
    }
}
