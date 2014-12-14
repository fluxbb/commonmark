<?php

namespace FluxBB\Markdown\Node;

abstract class Node implements NodeAcceptorInterface
{

    /**
     * @var Node
     */
    protected $parent = null;


    /**
     * Set a node as parent of this node.
     *
     * @param Node $parent
     * @return void
     */
    protected function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Accept a visit from a node visitor.
     *
     * This method should instrument the visitor to handle this node correctly, and also pass it on to any child nodes.
     *
     * @param NodeVisitorInterface $visitor
     * @return void
     */
    abstract public function visit(NodeVisitorInterface $visitor);

}
