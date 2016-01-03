# php-simply-html

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/emmanuelroecker/php-simply-html/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/emmanuelroecker/php-simply-html/?branch=master)
[![Build Status](https://travis-ci.org/emmanuelroecker/php-simply-html.svg?branch=master)](https://travis-ci.org/emmanuelroecker/php-simply-html)
[![Coverage Status](https://coveralls.io/repos/emmanuelroecker/php-simply-html/badge.svg?branch=master&service=github)](https://coveralls.io/github/emmanuelroecker/php-simply-html?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f2f6d5fe-633a-4318-9136-d2abeaf61419/mini.png)](https://insight.sensiolabs.com/projects/f2f6d5fe-633a-4318-9136-d2abeaf61419)

Add, delete, modify, read html tags by using css selector.

Get all text, links, summary inside html file.

It's working with [PHP DOM Extension](http://php.net/manual/en/book.dom.php) and [Symfony CssSelector](http://symfony.com/doc/current/components/css_selector.html)

## Installation

This library can be found on [Packagist](https://packagist.org/packages/glicer/simply-html).

The recommended way to install is through [composer](http://getcomposer.org).

Edit your `composer.json` and add:

```json
{
    "require": {
       "glicer/simply-html": "dev-master"
    }
}
```

And install dependencies:

```bash
php composer.phar install
```

## How to modify html ?

```php
<?php
// Must point to composer's autoload file.
require 'vendor/autoload.php';

use GlHtml\GlHtml;

//read index.html contents
$html = file_get_contents("index.html");

$dom = new GlHtml($html);

//delete all style tags inside head
$dom->delete('head style');

//prepare a new style tag
$style = '<link href="solver.css" type="text/css" rel="stylesheet"></link>';

//add the new style tag
$dom->get("head")[0]->add($style);

//write result in a new html file
file_put_contents("result.html",$dom->html());
```

## How to get all text inside html ?

```php
<?php
// Must point to composer's autoload file.
require 'vendor/autoload.php';

use GlHtml\GlHtml;

//read index.html contents
$html = file_get_contents("index.html");

$dom = new GlHtml($html);

//array of string sentences
$sentences = $dom->getSentences();

print_r($sentences);
```

## How to get all links inside html ?

```php
<?php
// Must point to composer's autoload file.
require 'vendor/autoload.php';

use GlHtml\GlHtml;

//read index.html contents
$html = file_get_contents("index.html");

$dom = new GlHtml($html);

//array of string url
$links = $dom->getLinks();

print_r($links);
```

## How to extract html headings (h1,h2,...,h6)?

```php
<?php
// Must point to composer's autoload file.
require 'vendor/autoload.php';

use GlHtml\GlHtml;

//read index.html contents
$html = file_get_contents("index.html");

$dom = new GlHtml($html);

//array of GlHtmlSummary object
$summary = $dom->getSummary();

echo $summary[0]->getNode()->getText() . ' ' . $summary[0]->getLevel();
```

## Running Tests

Launch from command line :

```console
vendor\bin\phpunit
```

## License MIT

## Contact

Authors : Emmanuel ROECKER & Rym BOUCHAGOUR

[Web Development Blog - http://dev.glicer.com](http://dev.glicer.com)
