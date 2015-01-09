<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Node;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class Heading extends Node
{

    /**
     * @var Text
     */
    protected $text;

    protected $level;


    public function __construct(Text $text, $level)
    {
        $this->text = $text;
        $this->level = $level;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitHeading($this);
    }

}
