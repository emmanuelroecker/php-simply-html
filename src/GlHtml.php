<?php
/**
 * Main Class to manipulate dom
 *
 * PHP version 5.4
 *
 * @category  GLICER
 * @package   GlHtml
 * @author    Emmanuel ROECKER
 * @author    Rym BOUCHAGOUR
 * @copyright 2015 GLICER
 * @license   MIT
 * @link      http://dev.glicer.com/
 *
 * Created : 19/02/15
 * File : GlHtml.php
 *
 */


namespace GlHtml;

use Symfony\Component\CssSelector\CssSelector;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * Class GlHtml
 * @package GlHtml
 */
class GlHtml
{
    /**
     * @var \DOMDocument
     */
    private $dom;

    /**
     * @var string
     */
    private $html;

    /**
     * @param string $html
     */
    public function __construct($html)
    {
        $html      = static::fixNewlines($html);
        $this->dom = new \DOMDocument();

        $libxml_previous_state = libxml_use_internal_errors(true); //disable warnings
        $this->dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();
        libxml_use_internal_errors($libxml_previous_state);

        $this->html = $html;
    }

    /**
     * Unify newlines
     *
     * @param string $text
     *
     * @return string the fixed text
     */
    static private function fixNewlines($text)
    {
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);

        return $text;
    }

    /**
     * return one dom element with $selector css filter
     *
     * @param string $selector CSS 3 Selector
     *
     * @return GlHtmlNode[]
     */
    public function get($selector)
    {
        $xpath = new \DOMXPath($this->dom);

        if (class_exists('Symfony\Component\CssSelector\CssSelector')) {
            $expression = CssSelector::toXPath($selector);
        } else {
            $converter  = new CssSelectorConverter();
            $expression = $converter->toXPath($selector);
        }
        $nodes = $xpath->query($expression);

        $glnodes = [];
        foreach ($nodes as $node) {
            $glnodes[] = new GlHtmlNode($node);
        }

        return $glnodes;
    }

    /**
     * set a list of attributes
     *
     * @param string $selector
     * @param array  $attributes
     */
    public function setAttributes($selector, array $attributes)
    {
        $nodes = $this->get($selector);

        foreach ($nodes as $node) {
            $node->setAttributes($attributes);
        }
    }

    /**
     * @param string $selector
     */
    public function delete($selector)
    {
        $nodes = $this->get($selector);
        foreach ($nodes as $node) {
            $node->delete();
        }
    }

    /**
     * @return string
     */
    public function html()
    {
        return $this->dom->saveHTML();
    }

    public function getText()
    {
        $body = $this->get("body")[0];

        return $body->getText();
    }

    /**
     * @param string $tagname
     * @param string $attribute
     * @param array  $links
     */
    private function getLinksByTagAttribute($tagname, $attribute, array &$links)
    {
        $tagslink = $this->get($tagname);
        foreach ($tagslink as $taglink) {
            $href = $taglink->getAttribute($attribute);
            if (isset($href) && (strlen(trim($href)) > 0)) {
                $links[$href] = $href;
            }
        }
    }

    /**
     * @param bool $all if true get url in text and params
     *
     * @return array
     */
    public function getLinks($all = false)
    {
        $links = [];

        $this->getLinksByTagAttribute("link", "href", $links);
        $this->getLinksByTagAttribute("a", "href", $links);
        $this->getLinksByTagAttribute("script", "src", $links);
        $this->getLinksByTagAttribute("iframe", "src", $links);
        $this->getLinksByTagAttribute("img", "src", $links);

        //get all string started with http
        $regexUrl = '/[">\s]+((http|https|ftp|ftps)\:\/\/(.*?))["<\s]+/';
        $urls     = null;
        if (preg_match_all($regexUrl, $this->html, $urls) > 0) {
            $matches = $urls[1];
            foreach ($matches as $url) {
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $links[$url] = $url;
                }
            }
        }

        if ($all) {
            //get all params which can be a url
            $regexParam = '/["](.*?)["]/';
            $params     = [];
            if (preg_match_all($regexParam, $this->html, $params) > 0) {
                $urls = $params[1];
                foreach ($urls as $url) {
                    $url = trim($url);
                    if ((strpbrk($url, "/.") !== false) && (strpbrk($url, " ") === false)) {
                        $links[$url] = $url;
                    }
                }
            }
        }

        foreach ($links as $link) {
            $url = parse_url($link);
            if (!((isset($url['host']) && isset($url['scheme'])) || (isset($url['path'])))) {
                unset($links[$link]);
            }
        }

        return $links;
    }

    public function getSentences()
    {
        $sentences = [];

        $body = $this->get("body");
        if (count($body) > 0) {
            $sentences = $body[0]->getSentences();
        }

        $description = $this->get('meta[name="description"]');
        if (count($description) > 0) {
            $description = trim($description[0]->getAttribute("content"));
            if (strlen($description) > 0) {
                array_unshift($sentences, $description);
            }
        }

        $title = $this->get('title');
        if (count($title) > 0) {
            $title = trim($title[0]->getText());
            if (strlen($title) > 0) {
                array_unshift($sentences, $title);
            }
        }

        return $sentences;
    }

    /**
     * @return GlHtmlSummary[]
     */
    public function getSummary()
    {
        $body = $this->get("body")[0];

        $summary  = [];
        $callback = function (GlHtmlNode $childNode) use (&$summary) {
            $nodeName = $childNode->getName();

            if (preg_match('/^h(\d+)$/', $nodeName, $matches)) {
                $summary[] = new GlHtmlSummary($childNode, $matches[1]);
            }
        };

        $body->callChild($callback);

        return $summary;
    }
}
