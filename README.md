An extensible CommonMark parser for PHP
=======================================

[![Latest Stable Version](https://img.shields.io/github/release/fluxbb/commonmark.svg?style=flat-square)](https://github.com/fluxbb/commonmark/releases)
[![Build Status](https://img.shields.io/travis/fluxbb/commonmark/master.svg?style=flat-square)](https://travis-ci.org/fluxbb/commonmark)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

An object oriented, fully extensible CommonMark parser for PHP 5.4 and above.

[**CommonMark spec**][commonmark-spec]

* Forked from [Ciconia](https://github.com/kzykhys/ciconia) by [Kazuyuki Hayashi](https://github.com/kzykhys)
* [Github Flavored Markdown](https://help.github.com/articles/github-flavored-markdown) support (disabled by default)
	* Multiple underscores in words
	* New lines
	* Fenced code blocks
	* Task lists
	* Table
	* URL Autolinking
* Tested to comply with the [full CommonMark spec test suite][commonmark-spec]

## Requirements

* PHP 5.4+
* Composer

## Installation

Add the library to your Composer dependencies:

	composer require fluxbb/commonmark

Next, use Composer to install the library and its dependencies:

	composer install

## Usage

### Traditional Markdown

```php
use FluxBB\CommonMark\Parser;

$parser = new Parser();
$html = $parser->render('Markdown is **awesome**');

// <p>Markdown is <em>awesome</em></p>
```

### Options

Option             | Type    | Default | Description                   |
-------------------|---------|---------|-------------------------------|
**strict**         | boolean | false   | Throws exception if markdown contains syntax error |

``` php
use FluxBB\CommonMark\Parser;

$parser = new Parser();
$html = $parser->render(
    'Markdown is **awesome**',
    ['strict' => true]
);
```

Rendering
---------

The parser renders XHTML by default.

## Extensions

### How to Extend

Creating extensions is easy, you only need to implement the `FluxBB\CommonMark\Extension\ExtensionInterface`.

Your class must implement two methods.

#### _void_ register(`FluxBB\CommonMark\Markdown` $markdown)

Register any callbacks with the markdown event manager.
`FluxBB\CommonMark\Markdown` is an instance of the `FluxBB\CommonMark\Event\EmitterInterface` (similar to Node's EventEmitter)

#### _string_ getName()

Returns the name of your extension.
If your name is the same as one of the core extensions, the latter will be replaced by your extension.

### Extension Example

This sample extension turns any `@username` mentions into links.

``` php
<?php

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Extension\ExtensionInterface;

class MentionExtension implements ExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(\FluxBB\CommonMark\Markdown $markdown)
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

$parser = new \FluxBB\CommonMark\Parser();
$parser->addExtension(new MentionExtension());
echo $parser->render('@admin my email address is example@example.com!');
```

Output

``` html
<p><a href="http://example.com/user/admin">@admin</a> my email address is example@example.com!</p>
```

Each extension handles string as a `Text` object. See [API section of kzykhys/Text][textapi].

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
	--compress (-c)       Remove whitespace between HTML tags
	--lint (-l)           Syntax check only (lint)
```

### Using PHAR version

You can also use a [single phar file][phar]

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


[milestones]: https://github.com/fluxbb/commonmark/issues/milestones
[phar]: https://github.com/fluxbb/commonmark/releases/download/v9.0/markdown.phar
[contributors]: https://github.com/fluxbb/commonmark/graphs/contributors
[textapi]: https://github.com/kzykhys/Text#api

[commonmark-spec]: http://spec.commonmark.org/
