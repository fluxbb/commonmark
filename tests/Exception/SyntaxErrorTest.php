<?php


class SyntaxErrorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException FluxBB\Markdown\Exception\SyntaxError
     * @expectedExceptionMessage [link] Unable to find id "id" in Reference-style link at line 3
     */
    public function testSyntaxErrorPointsRightLineNumber()
    {
        $md = new \FluxBB\Markdown\Parser();
        $md->render(<<< EOL
This is a paragraph

This is a [paragraph][id].

<pre>
preformatted text
</pre>
EOL
, array('strict' => true)
);
    }

}
