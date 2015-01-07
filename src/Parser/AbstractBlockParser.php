<?php

namespace FluxBB\CommonMark\Parser;

use FluxBB\CommonMark\Node\NodeAcceptorInterface;

abstract class AbstractBlockParser implements BlockParserInterface
{

    /**
     * @var NodeAcceptorInterface
     */
    protected $stack;

    /**
     * @var BlockParserInterface
     */
    protected $next;

    /**
     * @var BlockParserInterface
     */
    protected $first;


    public function setNextParser(BlockParserInterface $next)
    {
        $this->next = $next;
    }

    public function setFirstParser(BlockParserInterface $first)
    {
        $this->first = $first;
    }

    public function setStack(NodeAcceptorInterface $stack)
    {
        $this->stack = $stack;
    }

}
