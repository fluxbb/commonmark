<?php

namespace FluxBB\CommonMark\Parser;

abstract class AbstractInlineParser implements InlineParserInterface
{

    /**
     * @var InlineParserInterface
     */
    protected $next;


    public function setNextParser(InlineParserInterface $next)
    {
        $this->next = $next;
    }

}
