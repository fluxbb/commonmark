<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class ListBlock extends Container
{

    protected $start;


    public function __construct($type, $start = null)
    {
        $this->type = $type;
        $this->start = $start;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getStart()
    {
        return $this->start ?: 1;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterListBlock($this);

        parent::visit($visitor);

        $visitor->leaveListBlock($this);
    }

}
