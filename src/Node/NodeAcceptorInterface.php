<?php

namespace FluxBB\Markdown\Node;

interface NodeAcceptorInterface
{

    public function acceptParagraph(Paragraph $paragraph);

    public function acceptBlockquote(Blockquote $blockquote);

    public function acceptHeading(Heading $heading);

}
