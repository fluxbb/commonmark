<?php

class SyntaxErrorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException FluxBB\CommonMark\Exception\SyntaxError
     * @expectedExceptionMessage [link] Unable to find id "id" in Reference-style link at line 3
     */
    public function testSyntaxErrorPointsRightLineNumber()
    {
        $parser = new \FluxBB\CommonMark\DocumentParser();

        $markdown = <<< EOL
This is a paragraph

This is a [paragraph][id].

<pre>
preformatted text
</pre>
EOL;

        $parser->convert($markdown);
    }

}
