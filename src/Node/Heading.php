<?php

namespace FluxBB\Markdown\Node;

use FluxBB\Markdown\Common\Text;

class Heading extends LeafBlock implements NodeAcceptorInterface
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
        $visitor->enterHeading($this);

        $visitor->leaveHeading($this);
    }

}
