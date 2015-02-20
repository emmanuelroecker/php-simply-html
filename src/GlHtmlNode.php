<?php
/**
 * Extend \DomNode
 *
 * PHP version 5.4
 *
 * @category  GLICER
 * @package   GlHtml
 * @author    Emmanuel ROECKER
 * @author    Rym BOUCHAGOUR
 * @copyright 2015 GLICER
 * @license   MIT
 * @link      http://www.glicer.com/solver
 *
 * Created : 19/02/15
 * File : GlHtmlNode.php
 *
 */


namespace GlHtml;

/**
 * Class GlHtmlNode
 * @package GlHtml
 */
class GlHtmlNode
{
    /**
     * @var \DOMNode
     */
    private $node;

    /**
     * @param \DOMNode $node
     */
    public function __construct(\DOMNode $node)
    {
        $this->node = $node;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->node->setAttribute($name, $value);
        }
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->node->nodeValue = $value;
    }

    /**
     * @param string $html
     */
    public
    function add(
        $html
    ) {
        $frag = $this->node->ownerDocument->createDocumentFragment();
        $frag->appendXML($html);
        $this->node->appendChild($frag);
    }

    /**
     * @param string $html
     */
    public
    function replaceMe(
        $html
    ) {
        $frag = $this->node->ownerDocument->createDocumentFragment();
        $frag->appendXML($html);
        $this->node->parentNode->parentNode->replaceChild($frag, $this->node->parentNode);
    }

    /**
     * @param string $html
     */
    public function replaceInner($html)
    {
        $this->setValue('');
        $this->add($html);
    }


    /**
     * @return \DOMNode
     */
    public function getDOMNode()
    {
        return $this->node;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->node->nodeName;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public
    function getAttribute(
        $name
    ) {
        return $this->node->getAttribute($name);
    }

    /**
     * @return string
     */
    public
    function getText()
    {
        return $this->node->nodeValue;
    }


    public
    function delete()
    {
        $this->node->parentNode->removeChild($this->node);
    }

    /**
     * @return string
     */
    public
    function getHtml()
    {
        $innerHTML = '';
        $children  = $this->node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $child->ownerDocument->saveXML($child);
        }

        return $innerHTML;
    }
}
