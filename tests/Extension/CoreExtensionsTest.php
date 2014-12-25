<?php

use FluxBB\CommonMark\Parser;
use FluxBB\CommonMark\Renderer\XhtmlRenderer;
use Symfony\Component\Finder\Finder;

/**
 * Tests FluxBB\Extensions\Core\*
 *
 * @group Markdown
 * @group MarkdownCore
 *
 * @author Kazuyuki Hayashi <hayashi@siance.co.jp>
 */
class CoreExtensionsTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test an email link
     */
    public function testAutoLinkEmail()
    {
        $ciconia  = new Parser();
        $markdown = 'Email <test@example.com>';
        $output   = $ciconia->render($markdown);
        $expected = '<p>Email <a href="mailto:test@example.com">test@example.com</a></p>';

        $this->assertEquals($expected, html_entity_decode($output));
    }

    /**
     * On strict mode
     *
     * @param string $name     Name of the test case
     * @param string $markdown The Markdown content
     * @param string $expected Expected output
     *
     * @dataProvider strictModeProvider
     * @expectedException \FluxBB\CommonMark\Exception\SyntaxError
     */
    public function testStrictMode($name, $markdown, $expected)
    {
        $ciconia = new Parser();
        $html    = $ciconia->render($markdown, ['strict' => true]);

        $this->assertEquals($expected, $html, sprintf('%s failed', $name));
    }

    /**
     * @return array
     */
    public function strictModeProvider()
    {
        $finder = Finder::create()
            ->in(__DIR__.'/../Resources/options/strict/core')
            ->files()
            ->name('*.md');

        return $this->processPatterns($finder);
    }

    /**
     * @param Finder|\Symfony\Component\Finder\SplFileInfo[] $finder
     *
     * @return array
     */
    protected function processPatterns(Finder $finder)
    {
        $patterns = [];

        foreach ($finder as $file) {
            $name       = preg_replace('/\.(md|out)$/', '', $file->getFilename());
            $expected   = trim(file_get_contents(preg_replace('/\.md$/', '.out', $file->getPathname())));
            $expected   = str_replace("\r\n", "\n", $expected);
            $expected   = str_replace("\r", "\n", $expected);
            $patterns[] = [$name, $file->getContents(), $expected];
        }

        return $patterns;
    }

}
