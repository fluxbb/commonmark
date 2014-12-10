<?php

namespace FluxBB\Markdown\Console\Command;

use FluxBB\Markdown\DocumentParser;
use FluxBB\Markdown\Parser;
use FluxBB\Markdown\Renderer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command-line interface for parsing Markdown files in CommonMark format.
 *
 * @author Franz Liedke <franz@fluxbb.org>
 */
class CommonMarkCommand extends Command
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cm')
            ->setDescription('Translates CommonMark into HTML and displays it to STDOUT.')
            ->addArgument('file', InputArgument::OPTIONAL, 'The input file')
            ->setHelp($this->getHelpContent())
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $content = $this->handleInput($input);
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            $this->runHelp($input, $output);

            return 1;
        }

        $parser = new DocumentParser();
        $document = $parser->parse($content);

        $renderer = new Renderer();
        $html = $renderer->render($document);

        $output->write($html, false, OutputInterface::OUTPUT_RAW);

        return 0;
    }

    /**
     * Get a markdown content from input
     *
     * __Warning: Reading from STDIN always fails on Windows__
     *
     * @param InputInterface $input
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function handleInput(InputInterface $input)
    {
        if ($file = $input->getArgument('file')) {
            if (!file_exists($file)) {
                throw new \InvalidArgumentException(sprintf('The input file "%s" not found', $file));
            }

            return file_get_contents($file);
        } else {
            $contents = '';

            if ($stdin = fopen('php://stdin', 'r')) {
                // Warning: stream_set_blocking always fails on Windows
                if (stream_set_blocking($stdin, false)) {
                    $contents = stream_get_contents($stdin);
                }

                fclose($stdin);
            }

            // @codeCoverageIgnoreStart
            if ($contents) {
                return $contents;
            }
            // @codeCoverageIgnoreEnd
        }

        throw new \InvalidArgumentException('No input file');
    }

    /**
     * Runs help command
     *
     * @param InputInterface  $input  The InputInterface instance
     * @param OutputInterface $output The OutputInterface instance
     *
     * @return int
     */
    protected function runHelp(InputInterface $input, OutputInterface $output)
    {
        /* @var HelpCommand $help */
        $help = $this->getApplication()->find('help');
        $help->setCommand($this);
        $help->run($input, $output);
    }

    /**
     * --help
     *
     * @return string
     */
    protected function getHelpContent()
    {
        return <<< EOT
Translates CommonMark into HTML and displays to STDOUT
  <info>%command.name% /path/to/file.md</info>

Following command saves result to file
  <info>%command.name% /path/to/file.md > /path/to/file.html</info>

Or using pipe (On Windows it does't work)
  <info>echo "CommonMark is **awesome**" | %command.name%</info>
EOT;
    }

}
