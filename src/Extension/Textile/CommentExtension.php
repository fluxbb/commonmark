<?php

namespace FluxBB\CommonMark\Extension\Textile;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Extension\ExtensionInterface;
use FluxBB\CommonMark\Markdown;

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
