<?php

namespace FluxBB\CommonMark\Node;

use FluxBB\CommonMark\Common\Text;

class Image extends Node
{

    protected $source;

    protected $altText;

    protected $titleText;


    public function __construct(Text $source, Text $altText, Text $titleText = null)
    {
        $this->source = $source;
        $this->altText = $altText;
        $this->titleText = $titleText ?: new Text();
    }

    /**
     * @return Text
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return Text
     */
    public function getAltText()
    {
        return $this->altText;
    }

    /**
     * @return Text
     */
    public function getTitleText()
    {
        return $this->titleText;
    }

    /**
     * Accept a visit from a node visitor.
     *
     * This method should instrument the visitor to handle this node correctly, and also pass it on to any child nodes.
     *
     * @param NodeVisitorInterface $visitor
     * @return void
     */
    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitImage($this);
    }

}
