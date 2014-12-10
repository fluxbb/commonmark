<?php

namespace FluxBB\Markdown\Node;

interface NodeVisitorInterface
{

    public function enterParagraph(Paragraph $paragraph);

    public function leaveParagraph(Paragraph $paragraph);

    public function enterBlockquote(Blockquote $blockquote);

    public function leaveBlockquote(Blockquote $blockquote);

    public function enterHeading(Heading $heading);

    public function leaveHeading(Heading $heading);

    public function visitHorizontalRule(HorizontalRule $horizontalRule);

}
