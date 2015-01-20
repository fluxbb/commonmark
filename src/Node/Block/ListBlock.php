<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class ListBlock extends Container
{

    protected $type;

    protected $terse;

    protected $start;


    public function __construct($type, $terse, $start = null)
    {
        $this->type = $type;
        $this->terse = $terse;
        $this->start = $start;
    }

    public function getType()
    {
        return $this->type;
    }

    public function isTerse()
    {
        return (bool) $this->terse;
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
