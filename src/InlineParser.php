<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Node\CodeBlock;
use FluxBB\Markdown\Node\Heading;
use FluxBB\Markdown\Node\HorizontalRule;
use FluxBB\Markdown\Node\NodeVisitorInterface;
use FluxBB\Markdown\Node\Paragraph;

class InlineParser implements NodeVisitorInterface
{

    public function enterParagraph(Paragraph $paragraph)
    {
        return;
    }

    public function leaveParagraph(Paragraph $paragraph)
    {
        return;
    }

    public function enterBlockquote(Blockquote $blockquote)
    {
        return;
    }

    public function leaveBlockquote(Blockquote $blockquote)
    {
        return;
    }

    public function enterHeading(Heading $heading)
    {
        return;
    }

    public function leaveHeading(Heading $heading)
    {
        return;
    }

    public function visitHorizontalRule(HorizontalRule $horizontalRule)
    {
        return;
    }

    public function visitCodeBlock(CodeBlock $codeBlock)
    {
        return;
    }

}
