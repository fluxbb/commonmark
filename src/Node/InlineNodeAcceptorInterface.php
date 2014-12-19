<?php

namespace FluxBB\Markdown\Node;

interface InlineNodeAcceptorInterface
{

    /**
     * Add an inline element.
     *
     * @param Node $inline
     * @return void
     */
    public function addInline(Node $inline);

    /**
     * Return all inline elements.
     *
     * @return Node[]
     */
    public function getInlines();

}
