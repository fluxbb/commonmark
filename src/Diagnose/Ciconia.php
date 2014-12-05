<?php

namespace FluxBB\Markdown\Diagnose;

use FluxBB\Markdown\Parser as BaseCiconia;
use FluxBB\Markdown\Common\Text;

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
