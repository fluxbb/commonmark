<?php

use FluxBB\Markdown\Parser;
use FluxBB\Markdown\Renderer\XhtmlRenderer;
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
     * Test CommonMark spec examples
     *
     * See `test/Resources/spec.txt`
     *
     * @param string $name
     * @param string $markdown
     * @param string $expected
     * @param string $section
     *
     * @dataProvider commonMarkSpecProvider
     */
    public function testWithCommonMarkSpec($name, $markdown, $expected, $section)
    {
        $parser = new Parser(new XhtmlRenderer());
        $html = $parser->render($markdown);

        $this->assertEquals($expected, $html, "$section: $name failed");
    }

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
     * @expectedException \FluxBB\Markdown\Exception\SyntaxError
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
    public function commonMarkSpecProvider()
    {
        $lines = file(__DIR__.'/../Resources/CommonMark/spec.txt', FILE_IGNORE_NEW_LINES);

        $state = 0; // 0 regular text, 1 markdown example, 2 html output
        $startLine = 0;
        $lineNumber = 0;
        $exampleNumber = 0;
        $headerText = '';
        $markdownLines = [];
        $htmlLines = [];

        $tests = [];
        foreach ($lines as $line) {
            $lineNumber++;

            if ($state == 0 && preg_match('/#+ /', $line)) {
                $headerText = trim(preg_replace('/#+ /', '', $line));
            }

            if (trim($line) == '.') {
                $state = ($state + 1) % 3;
                if ($state == 0) {
                    $exampleNumber++;
                    $endLine = $lineNumber;

                    $tests[] = [
                        "#$exampleNumber (lines $startLine - $endLine)",
                        str_replace('→', "\t", implode("\n", $markdownLines)),
                        implode("\n", $htmlLines),
                        $headerText,
                    ];

                    $startLine = 0;
                    $markdownLines = [];
                    $htmlLines = [];
                }
            } elseif ($state == 1) {
                if ($startLine == 0) {
                    $startLine = $lineNumber - 1;
                }
                $markdownLines[] = $line;
            } elseif ($state == 2) {
                $htmlLines[] = $line;
            }
        }

        return $tests;
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
