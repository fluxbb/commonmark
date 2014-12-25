<?php

namespace FluxBB\CommonMark\Diagnose;

use FluxBB\CommonMark\Parser as BaseCiconia;
use FluxBB\CommonMark\Common\Text;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Ciconia extends BaseCiconia
{

    /**
     * @param string $text
     * @param array  $options
     *
     * @return Event[]|string
     */
    public function render($text, array $options = array())
    {
        $text = new Text($text);
        $markdown = new Markdown($this->getRenderer(), $text, $options);

        $this->registerExtensions($markdown);

        $markdown->start();
        $markdown->emit('initialize', array($text));
        $markdown->emit('block', array($text));
        $markdown->emit('finalize', array($text));

        return $markdown->stop();
    }

} 
