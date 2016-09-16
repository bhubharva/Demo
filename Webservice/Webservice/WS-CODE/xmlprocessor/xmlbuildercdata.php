<?php
/**
 * @author Ed Aspra
 * @since  October 16, 2007
 * Extended XML Builder Class to include <![[CDATA]]>
 */

require_once(CFG_LIB_PLUGINS.'xmlprocessor/xmlbuilder.php');

class XmlBuilderCDATA extends XmlBuilder
{

	function XmlBuilderCDATA($indent = '  ') {
		$this->indent = $indent;
		$this->xml = '<?xml version="1.0" encoding="utf-8"?>'."\n";
	}
	//Used when an element has sub-elements
	// This function adds an open tag to the output
	function PushCDATA($element, $attributes = array(), $cdata = 0) {
		$this->_indent();
		$this->xml .= '<'.$element;
		if(is_array($attributes) && !empty($attributes)) {
			//added if-condition above, else foreach will break when $attributes array is empty [By Krishna@Cybage: 05-Jun-2013]
			foreach ($attributes as $key => $value) {
				if ($cdata) {
					$this->xml .= ' '.$key.'="<![CDATA['.htmlentities($value).']]>"';
				}else{
					$this->xml .= ' '.$key.'="'.htmlentities($value).'"';
				}
			}
		}
		$this->xml .= ">\n";
		$this->stack[] = $element;
	}

	//Used when an element has no subelements.
	//Data within the open and close tags are provided with the
	//contents variable
	function ElementCDATA($element, $content, $attributes = array(), $cdata = 0) {
		$this->_indent();
		$this->xml .= '<'.$element;
		if(is_array($attributes) && !empty($attributes)) {
			//added if-condition above, else foreach will break when $attributes array is empty [By Krishna@Cybage: 05-Jun-2013]
			foreach ($attributes as $key => $value) {
				if ($cdata) {
					$this->xml .= ' '.$key.'="<![CDATA['.htmlentities($value).']]>"';
				}else{
					$this->xml .= ' '.$key.'="'.htmlentities($value).'"';
				}
			}
		}
		if ($cdata) {
			$this->xml .= '><![CDATA['.htmlentities($content).']]></'.$element.'>'."\n";
		}else{
			$this->xml .= '>'.htmlentities($content).'</'.$element.'>'."\n";
		}
	}

	function EmptyElement($element, $attributes = array(),$cdata = 0) {
		$this->_indent();
		$this->xml .= '<'.$element;
		if(is_array($attributes) && !empty($attributes)) {
			//added if-condition above, else foreach will break when $attributes array is empty [By Krishna@Cybage: 05-Jun-2013]
			foreach ($attributes as $key => $value) {
				if ($cdata) {
					$this->xml .= ' '.$key.'="'.htmlentities($value).'"';
				}else{
					$this->xml .= ' '.$key.'="<![CDATA['.htmlentities($value).']]>"';
				}
			}
		}
		$this->xml .= " />\n";
	}

}
?>
