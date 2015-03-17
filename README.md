php-simply-html
===============
Add, delete, modify, read html tags by using css selector.

Get all text, links, summary inside html file.

# Installation

This library can be found on [Packagist](https://packagist.org/packages/glicer/simply-html).

The recommended way to install this is through [composer](http://getcomposer.org).

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

# How to modify html ?

```php
     <?php
     // Must point to composer's autoload file.
     require 'vendor/autoload.php';

     use GlHtml\GlHtml;

     //read index.html contents
     $html = file_get_contents("index.html");

     $html = new GlHtml($html);

     //delete all style tags inside head
     $html->delete('head style');

     //prepare a new style tag
     $style = '<link href="solver.css" type="text/css" rel="stylesheet"></link>';

     //add the new style tag
     $html->get("head")[0]->add($style);

     //write result in a new html file
     file_put_contents("result.html",$html->html());
```

# How to get all text inside html ?

```php
     // Must point to composer's autoload file.
     require 'vendor/autoload.php';

     use GlHtml\GlHtml;

     //read index.html contents
     $html = file_get_contents("index.html");

     $html = new GlHtml($html);

     //array of string sentences
     $sentences = $html->getSentences();

     print_r($sentences);
```

# How to get all links inside html ?

```php
     // Must point to composer's autoload file.
     require 'vendor/autoload.php';

     use GlHtml\GlHtml;

     //read index.html contents
     $html = file_get_contents("index.html");

     $html = new GlHtml($html);

     //array of string url
     $links = $html->getLinks();

     print_r($links);
```

# How to extract html headings (h1,h2,...,h6)?

```php
     // Must point to composer's autoload file.
     require 'vendor/autoload.php';

     use GlHtml\GlHtml;

     //read index.html contents
     $html = file_get_contents("index.html");

     $html = new GlHtml($html);

     //array of GlHtmlSummary object
     $summary = $html->getSummary();

     echo $summary[0]->getNode()->getText() . ' ' . $summary[0]->getLevel();
```

# Contact

Authors : Emmanuel ROECKER & Rym BOUCHAGOUR

[Web Development Blog - http://dev.glicer.com](http://dev.glicer.com)
