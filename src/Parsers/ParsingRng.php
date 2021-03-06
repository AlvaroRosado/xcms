<?php

/**
 *  \details &copy; 2018 Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 *  Ximdex a Semantic Content Management System (CMS)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  See the Affero GNU General Public License for more details.
 *  You should have received a copy of the Affero GNU General Public License
 *  version 3 along with Ximdex (see LICENSE file).
 *
 *  If not, visit http://gnu.org/licenses/agpl-3.0.html.
 *
 * @author Ximdex DevTeam <dev@ximdex.com>
 * @version $Revision$
 */

namespace Ximdex\Parsers;

use DOMDocument;
use domNode;
use DOMXPath;
use Ximdex\Models\Node;
use Ximdex\Logger;

class ParsingRng
{
    const XMLNS_XIM = 'http://ximdex.com/schema/1.0';
    var $minimalXml = '';
    
    /**
     * @var DOMXPath
     */
    var $xpathObj;
    
    var $elementsForRender;
    var $renderCount = 0;
    var $nodesProcessed = array();
    var $idTemplate;
    var $node;

    function __construct(int $idTemplate = null)
    {
        if (! is_null($idTemplate)) {
            $this->node = new Node($idTemplate);
        }
    }

    /**
    * Gets the array needed for render a web form
    * 
    * @param int $templateID
    * @return array|NULL
    */
    function getElementsForRender(int $templateID)
    {
        $this->buildDefaultContent($templateID);
        $elements = $this->elementsForRender;
        if (is_null($elements)) {
            Logger::error("Incorrect visual template $templateID for render a web form");
            return null;
        }
        return $elements;
    }

    /**
     * Build an associative array of form elements based on RNG
     *
     * @param int $templateID RNG identifier
     * @param string RNG element name from which parser starts building the form elements
     * @return array Associative array of form elements [ array of array('name' => 'Element name', 'type' => 'Element type') ]
     */
    function buildFormElements(int $templateID, string $start = '')
    {
        $form_elements = array();
        $content = $this->buildDefaultContent($templateID);
        $domDoc = new DOMDocument();
        if ($domDoc->loadXML("<root>" . $content . "</root>")) {
            $xpathObj = new DOMXPath($domDoc);
            $elements = $xpathObj->query("//$start/*");
            if ($elements->length > 0) {
                foreach ($elements as $value) {
                    $form_elements[] = array(
                        'name' => $value->nodeName,
                        'type' => $value->getAttribute("input")
                    );
                }
            }
        }
        return $form_elements;
    }

    /**
     * Build an associative array of form elements based on RNG
     *
     * @param int $templateID RNG identifier
     * @param string RNG element name from which parser starts building the form elements
     * @return array Associative array of form elements [ array of array('name' => 'Element name', 'type' => 'Element type') ]
     */
    function buildFormElementsAssociative(int $templateID, string $start = '')
    {
        $form_elements = array();
        $content = $this->buildDefaultContent($templateID);
        $domDoc = new DOMDocument();
        if ($domDoc->loadXML("<root>" . $content . "</root>")) {
            $xpathObj = new DOMXPath($domDoc);
            $elements = $xpathObj->query("//$start/*");
            if ($elements->length > 0) {
                foreach ($elements as $value) {
                    $form_elements[$value->nodeName] = $value->getAttribute("input");
                }
            }
        }
        return $form_elements;
    }

    /**
     * Build a minimal XML from a relax NG schema
     * 
     * @param int $templateID
     * @return NULL|string
     */
    public function buildDefaultContent(int $templateID)
    {
        $node = new Node($templateID);
        if (! $node->get('IdNode')) {
            return null;
        }
        $content = $node->getContent();
        $domDoc = new DOMDocument();
        $domDoc->preserveWhiteSpace = false;
        $domDoc->validateOnParse = true;
        $domDoc->formatOutput = true;
        $domDoc->loadXML($content);
        $this->xpathObj = new DOMXPath($domDoc);

        // Gets the root element and starts parsing
        $nodeList0 = $this->xpathObj->query('//*[local-name(.)="element" and @name="docxap"]');
        
        // Check if the RNG template have the docxap item declared
        if ($nodeList0->item(0)) {
        	$nodeList = $nodeList0->item(0)->childNodes;
        	if ($nodeList->length > 0) {
            	foreach ($nodeList as $domNode) {
                	$this->processNode($domNode);
            	}
        	}
        }
        return $this->minimalXml;
    }

    /**
     * Proccess a node from a relax NG XML
     *
     * @param domNode $domNode
     */
    private function processNode(DOMNode $domNode)
    {
        $nodeName = $domNode->nodeName;
        switch ($nodeName) {
            case 'element':
                $this->processElement($domNode);
                break;
            case 'ref':
                $name = $domNode->attributes->getNamedItem('name')->nodeValue;
                /**
                 * @var $nodeList \DOMNodeList
                 */
                $nodeList = $this->xpathObj->query('//*[local-name(.)="define" and @name="' . $name . '"]');
                
                // FirstChild is a tag 'element' (always?)
                if ($nodeList->length > 0 
                    && isset($nodeList->item(0)->firstChild->attributes->getNamedItem('name')->nodeValue)
                    && ! isset($this->nodesProcessed[$nodeList->item(0)->firstChild->attributes->getNamedItem('name')->nodeValue])) {
                    $this->processElement($nodeList->item(0)->firstChild);
                }
                break;
            case 'optional':
            case 'zeroOrMore':
                break;
            case 'oneOrMore':
            case 'interleave':

                // Process the children
                if (in_array($domNode->firstChild->nodeName, array('choice', 'interleave'))) {
                    $nodeList = $domNode->firstChild->childNodes;
                } else {
                    $nodeList = $domNode->childNodes;
                }
                if ($nodeList->length > 0) {
                    foreach ($nodeList as $domNode) {
                        $this->processNode($domNode);
                    }
                }
                break;
            case 'xim:default_content':
                $this->minimalXml .= $domNode->nodeValue;
                break;
        }
    }

    /**
     * Proccess a 'element' node and sets variable minimalXml
     *
     * @param domNode $domNode
     */
    private function processElement(DOMNode $domNode)
    {
        $name = $domNode->attributes->getNamedItem('name')->nodeValue;
        $this->nodesProcessed[$name] = true;
        $this->minimalXml .= '<' . $name;
        $nodeList = $domNode->childNodes;

        // Process children
        if ($nodeList->length > 0) {
            $this->processAttributes($nodeList, $domNode->getAttribute('name'));
            $this->minimalXml .= '>';
            foreach ($nodeList as $domNode) {
                $this->processNode($domNode);
            }
            $this->minimalXml .= '</' . $name . '>';
        } else {
            $this->minimalXml .= '/>';
        }
    }

    /**
     * Searchs 'attribute' elements at a list of childs nodes and sets variables minimalXml and elementsForRender
     * 
     * @param \DOMNodeList $childNodes
     * @param string $parentName
     */
    private function processAttributes(\DOMNodeList $childNodes, string $parentName)
    {
        foreach ($childNodes as $domNode) {
            if ($domNode->nodeName == 'attribute') {
                $name = $domNode->attributes->getNamedItem('name')->nodeValue;
                $value = $domNode->attributes->getNamedItem('value');
                if (is_null($value)) {

                    // Has a choice tag?
                    if ($domNode->hasChildNodes()) {
                        $childName = $domNode->childNodes->item(0)->nodeName;
                        if ($childName == 'choice') {
                            $choiceNode = $domNode->childNodes->item(0);
                            
                            // Takes as attribute value the first 'value' tag
                            $value = $choiceNode->childNodes->item(0)->nodeValue;
                        }
                        if ($childName == 'xim:attribute') {
                            $this->processAttributeElement($domNode, $parentName);
                        }
                    }
                }
                if (in_array($name, array('type', 'label', 'id'))) {
                    $this->elementsForRender[$parentName][$name] = $value;
                    if ($name == 'id') {
                        $this->elementsForRender[$parentName]['name'] = $value;
                        $this->minimalXml .= " name=\"$value\"";
                    }
                }
                if ($name != 'uid') {
                    $this->minimalXml .= " $name=\"$value\"";
                }
            }
        }
        $this->renderCount++;
    }

    /**
     * Gets the element's attributes and sets variables minimalXml and elementsForRender
     *
     * @param domNode $domNode
     */
    private function processAttributeElement(DOMNode $domNode, string $parentName)
    {
        $name = $domNode->attributes->getNamedItem('name')->nodeValue;

        // Is allways the first child?

        $renderElement = $domNode->childNodes->item(0)->attributes->getNamedItem('renderElement')->nodeValue;
        if (! is_null($renderElement)) {
            $renderLabel = $domNode->childNodes->item(0)->attributes->getNamedItem('renderLabel')->nodeValue;
            $this->elementsForRender[$parentName] = array('name' => $name, 'id' => $name, 'type' => $renderElement, 'label' => $renderLabel);
            $this->renderCount++;
        }
    }

    public function getElementsByType(string $type)
    {
        $elementsNames = array();
        if (! $this->setXpathObj()) {
            return $elementsNames;
        }
        
        // Starts parsing
        $nodeList = $this->xpathObj->query('//*[local-name(.)="type"]');
        if ($nodeList->length > 0) {
            foreach ($nodeList as $domNode) {
                if ($domNode->nodeName == 'xim:type' && in_array($type, explode('|', $domNode->textContent))) {
                    $elementsNames[] = $domNode->parentNode->getAttribute('name');
                }
            }
        }
        return $elementsNames;
    }

    public function getElements()
    {
        $elementsNames = array();
        if (! $this->setXpathObj()) {
            return $elementsNames;
        }
        
        // Starts parsing
        $nodeList = $this->xpathObj->query('//*[local-name(.)="element"]');
        if ($nodeList->length > 0) {
            foreach ($nodeList as $domNode) {
                if ($domNode->nodeName == 'element') {
                    $elementsNames[] = $domNode->getAttribute('name');
                }
            }
        }
        return $elementsNames;
    }

    private function setXpathObj()
    {
        if (! $this->node || $this->node->nodeType->get('Name') != 'RngVisualTemplate') {
            return false;
        }
        $content = $this->node->GetContent();
        $domDoc = new DOMDocument();
        $domDoc->preserveWhiteSpace = false;
        $domDoc->validateOnParse = true;
        $domDoc->formatOutput = true;
        $domDoc->loadXML($content);
        $this->xpathObj = new DOMXPath($domDoc);
        return true;
    }
}
