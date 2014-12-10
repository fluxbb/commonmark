<?php

namespace FluxBB\Markdown\Node;

interface NodeAcceptorInterface
{

    /**
     * Accept the given node as a child.
     *
     * This should ask the node for its type, and then call the appropriate method.
     *
     * @param NodeInterface $node
     * @return Node
     */
    public function accept(NodeInterface $node);

    public function acceptParagraph(Paragraph $paragraph);

    public function acceptBlockquote(Blockquote $blockquote);

}
