An extensible Markdown parser for PHP
=====================================

[![Latest Stable Version](https://img.shields.io/github/release/fluxbb/markdown.svg?style=flat-square)](https://github.com/fluxbb/markdown/releases)
[![Build Status](https://img.shields.io/travis/fluxbb/markdown/master.svg?style=flat-square)](https://travis-ci.org/fluxbb/markdown)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Coverage Status](https://img.shields.io/coveralls/kzykhys/Ciconia/master.svg?style=flat-square)](https://coveralls.io/r/kzykhys/Ciconia?branch=master)

An object oriented, fully extensible markdown parser for PHP 5.4 and above.
It contains a collection of extensions, making it easy to replace, add or remove parsing mechanics.

[**Try Demo**][ciconia-demo] / [**Docs**][ciconia-docs] / [**Supported Syntax**][ciconia-syntax] / [**API Reference**][ciconia-api]

* Based on John Gruber's Markdown.pl
* Originally started by [Kazuyuki Hayashi](https://github.com/kzykhys)
* [Github Flavored Markdown](https://help.github.com/articles/github-flavored-markdown) support (disabled by default)
	* Multiple underscores in words
	* New lines
	* Fenced code blocks
	* Task lists
	* Table
	* URL Autolinking
* Tested with [karlcow/markdown-testsuite](https://github.com/karlcow/markdown-testsuite)

## Requirements

* PHP 5.4+
* Composer

## Installation

Add the library to your Composer dependencies:

	composer require fluxbb/markdown

Next, use Composer to install the library and its dependencies:

	composer install

## Usage

### Traditional Markdown

```php
use FluxBB\Markdown\Parsers;

$parser = new Parser();
$html = $parser->render('Markdown is **awesome**');

// <p>Markdown is <em>awesome</em></p>
```

### Github Flavored Markdown

To activate 6 gfm features:

``` php
use FluxBB\Markdown\Parser;
use FluxBB\Markdown\Extension\Gfm;

$parser = new Parser();
$parser->addExtension(new Gfm\FencedCodeBlockExtension());
$parser->addExtension(new Gfm\TaskListExtension());
$parser->addExtension(new Gfm\InlineStyleExtension());
$parser->addExtension(new Gfm\WhiteSpaceExtension());
$parser->addExtension(new Gfm\TableExtension());
$parser->addExtension(new Gfm\UrlAutoLinkExtension());

$html = $parser->render('Markdown is **awesome**');

// <p>Markdown is <em>awesome</em></p>
```

### Options

Option             | Type    | Default | Description                   |
-------------------|---------|---------|-------------------------------|
**tabWidth**       | integer | 4       | Number of spaces              |
**nestedTagLevel** | integer | 3       | Max depth of nested HTML tags |
**strict**         | boolean | false   | Throws exception if markdown contains syntax error |

``` php
use FluxBB\Markdown\Parser;

$parser = new Parser();
$html = $parser->render(
    'Markdown is **awesome**',
    ['tabWidth' => 8, 'nestedTagLevel' => 5, 'strict' => true]
);
```

Rendering HTML or XHTML
-----------------------

The parser renders HTML by default. If you prefer XHTML:

``` php
use FluxBB\Markdown\Parser;
use FluxBB\Markdown\Renderer\XhtmlRenderer;

$parser = new Parser(new XhtmlRenderer());
$html = $parser->render('Markdown is **awesome**');

// <p>Markdown is <em>awesome</em></p>
```

## Extensions

### How to Extend

Creating extensions is easy, you only need to implement the `FluxBB\Markdown\Extension\ExtensionInterface`.

Your class must implement two methods.

#### _void_ register(`FluxBB\Markdown\Markdown` $markdown)

Register any callbacks with the markdown event manager.
`FluxBB\Markdown\Markdown` is an instance of the `FluxBB\Markdown\Event\EmitterInterface` (similar to Node's EventEmitter)

#### _string_ getName()

Returns the name of your extension.
If your name is the same as one of the core extensions, the latter will be replaced by your extension.

### Extension Example

This sample extension turns any `@username` mentions into links.

``` php
<?php

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Extension\ExtensionInterface;

class MentionExtension implements ExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(\FluxBB\Markdown\Markdown $markdown)
    {
        $markdown->on('inline', [$this, 'processMentions']);
    }

    /**
     * @param Text $text
     */
    public function processMentions(Text $text)
    {
        // Turn @username into [@username](http://example.com/user/username)
        $text->replace('/(?:^|[^a-zA-Z0-9.])@([A-Za-z]+[A-Za-z0-9]+)/', function (Text $w, Text $username) {
            return '[@' . $username . '](http://example.com/user/' . $username . ')';
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mention';
    }
}
```

Register your extension.

``` php
<?php

require __DIR__ . '/vendor/autoload.php';

$parser = new \FluxBB\Markdown\Parser();
$parser->addExtension(new MentionExtension());
echo $parser->render('@admin my email address is example@example.com!');
```

Output

``` html
<p><a href="http://example.com/user/admin">@admin</a> my email address is example@example.com!</p>
```

Each extension handles string as a `Text` object. See [API section of kzykhys/Text][textapi].

### Events

Possible events are:

| Event      | Description                                                                               |
|------------|-------------------------------------------------------------------------------------------|
| initialize | Document level parsing. Called at the first of the sequence.                              |
| block      | Block level parsing. Called after `initialize`                                            |
| inline     | Inline level parsing. Generally called by block level parsers.                            |
| detab      | Convert tabs to spaces. Generally called by block level parsers.                          |
| outdent    | Remove one level of line-leading tabs or spaces. Generally called by block level parsers. |
| finalize   | Called after `block`                                                                      |

[See the source code of Extensions](src/Extension).

[See events and timing information](https://gist.github.com/kzykhys/7443440).

### Create your own Renderer

This library supports HTML/XHTML output. If you prefer customizing the output,
just create a class that implements `FluxBB\Markdown\Renderer\RendererInterface`.

See [FluxBB\Markdown\Renderer\RendererInterface](src/Renderer/RendererInterface.php).

## Command Line Interface

### Usage

Basic usage: (Outputs result to STDOUT)

    bin/markdown /path/to/file.md

The following command saves results to a file:

    bin/markdown /path/to/file.md > /path/to/file.html

Or using pipe (does not work on Windows):

    echo "Markdown is **awesome**" | bin/markdown

### Command Line Options

```
	--gfm                 Activate Gfm extensions
	--compress (-c)       Remove whitespace between HTML tags
	--format (-f)         Output format (html|xhtml) (default: "html")
	--lint (-l)           Syntax check only (lint)
```

### Using PHAR version

You can also use [single phar file][phar]

```
markdown.phar /path/to/file.md
```

If you prefer access this command globally, download [markdown.phar][phar] and move it into your `PATH`.

```
mv markdown.phar /usr/local/bin/markdown
```

Testing
-------

Install or update `dev` dependencies.

```
php composer.phar update --dev
```

and run `phpunit`

## License

The MIT License

## Contributing

Feel free to fork this repository and send pull requests. Take a look at [who has contributed so far][contributors].

## Author

A big thanks to Kazuyuki Hayashi (@kzykhys), who originally created this library.


[milestones]: https://github.com/fluxbb/markdown/issues/milestones
[phar]: https://github.com/fluxbb/markdown/releases/download/v.9.0/markdown.phar
[contributors]: https://github.com/fluxbb/markdown/graphs/contributors
[textapi]: https://github.com/kzykhys/Text#api

[ciconia-demo]: http://ciconia.kzykhys.com/
[ciconia-docs]: http://ciconia.kzykhys.com/docs/
[ciconia-syntax]: http://ciconia.kzykhys.com/syntax.html
[ciconia-api]: http://ciconia.kzykhys.com/api/
