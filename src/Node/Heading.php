<?php

namespace FluxBB\Markdown\Node;

use FluxBB\Markdown\Common\Text;

class Heading extends Block implements NodeInterface, NodeAcceptorInterface
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

    public function getType()
    {
        return 'heading';
    }

    public function toString()
    {
        return parent::toString() . '("' . $this->level . '")';
    }

    public function canContain(Node $other)
    {
        return true;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function proposeTo(NodeAcceptorInterface $block)
    {
        return $block->acceptHeading($this);
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterHeading($this);

        $visitor->leaveHeading($this);
    }

}
