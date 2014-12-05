<?php

namespace FluxBB\Markdown\Extension;

use FluxBB\Markdown\Markdown;

/**
 * FluxBB extensions must implement ExtensionInterface to register listeners to Markdown
 *
 * @author Kazuyuki Hayashi <hayashi@siance.co.jp>
 */
interface ExtensionInterface
{

    /**
     * Adds listeners to EventEmitter
     *
     * @api
     *
     * @param Markdown $markdown
     *
     * @return void
     */
    public function register(Markdown $markdown);

    /**
     * Returns the name of the extension
     *
     * @api
     *
     * @return string
     */
    public function getName();

}
