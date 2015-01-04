<?php

namespace FluxBB\CommonMark\Parser;

use FluxBB\CommonMark\InlineParser;

abstract class AbstractInlineParser implements InlineParserInterface
{

    /**
     * @var InlineParserInterface
     */
    protected $next;

    /**
     * @var InlineParser
     */
    protected $context;


    public function setNextParser(InlineParserInterface $next)
    {
        $this->next = $next;
    }

    public function setContext(InlineParser $context)
    {
        $this->context = $context;
    }

}
