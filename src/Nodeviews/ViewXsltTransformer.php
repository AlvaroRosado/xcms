<?php

/**
 *  \details &copy; 2019 Open Ximdex Evolution SL [http://www.ximdex.org]
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
 *  @author Ximdex DevTeam <dev@ximdex.com>
 *  @version $Revision$
 */

namespace Ximdex\Nodeviews;

use Ximdex\Logger;
use Ximdex\Runtime\App;

class ViewXsltTransformer extends AbstractView implements IView
{
    /**
     * {@inheritDoc}
     * @see \Ximdex\Nodeviews\AbstractView::transform()
     */
    public function transform(int $idVersion = null, string $content = null, array $args = null)
    {
		$xsltFile = '';	
		if (array_key_exists('XSLT', $args)) {
        	$xsltFile = $args['XSLT'];
		}
		if (! is_file(XIMDEX_ROOT_PATH . $xsltFile)) {
			Logger::error('No se ha encontrado la xslt solicitada ' . $xsltFile);
			return $content;
		}
		$xsltTransformer = new \Ximdex\XML\XSLT();
		$xsltTransformer->setXMLContent($content);
		$xsltTransformer->setXSL(XIMDEX_ROOT_PATH . $xsltFile);
		$transformedContent = $xsltTransformer->process();
		if ($transformedContent === false) {
		    return false;
		}
		$transformedContent = $this->fixDocumentEncoding($transformedContent);
		return $transformedContent;
	}
	
	private function fixDocumentEncoding(string $content) : string
	{	
		$doc = new \DOMDocument();
		$doc->formatOutput = true;
		$doc->preserveWhiteSpace = false;
		$doc->loadXML($content);
		
		// In this case the XSLT template does not provide an encoding
		if (empty($doc->encoding)) {	    
			$encoding = App::getValue( 'displayEncoding');
			$doc->encoding = $encoding;
			$content = $doc->saveXML();
		}
		return $content;
	}
}
