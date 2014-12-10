<?php

namespace FluxBB\Markdown\Node;

interface NodeInterface
{

    /**
     * @param NodeAcceptorInterface $block
     * @return Node
     */
    public function proposeTo(NodeAcceptorInterface $block);

}
