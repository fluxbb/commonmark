<?php

namespace FluxBB\CommonMark\Parser;

abstract class AbstractBlockParser implements BlockParserInterface
{

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

}
