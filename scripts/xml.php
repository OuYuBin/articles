<?php


#*****************************************************************************
#
# news.php
#
# Author: 		Wayne Beaton
# Date:			2005-11-07
#
# Description: Use the get_news($newsfile) function in this file to generate the html 
# equivalent of the provided RSS file. 
#
#****************************************************************************

/*
 * This function does the actual work of parsing the RSS
 * file into an object representation. We use a SAX parser
 * to do this.
 * 
 * Note that this function is intended to be used with relatively
 * short files (the entire file contents are loaded into memory).
 * If the RSS files start to get too large, this method will need
 * to be updated.
 */
function parse_xml_file($file_name, & $file_handler) {
	$GLOBALS[handler] = & $file_handler;
	$file_handler->initialize();
	
	// Read the entire file contents in to memory.
	// If file sizes get too large, we'll have to be smarter here.
	$file = fopen($file_name, "r");
	$xml = fread($file, filesize($file_name));
	fclose($file);

	$parser = xml_parser_create();
	xml_set_element_handler($parser, 'start_handler', 'end_handler');
	xml_set_character_data_handler($parser, 'data_handler');
	xml_parse($parser, $xml);
}

/*
 * The $handler variable is global. It manages all the call backs
 * that come (indirectly) from the SAX parser.
 */
$handler = null;

/*
 * SAX parser callback method to handle the start of an element.
 * This method just defers to the global handler to do the actual
 * work.
 */
function start_handler($parser, $name, $attributes) {
	global $handler;
	$handler->start($name, $attributes);
}

/*
 * SAX parser callback method to handle the text for an element.
 * This method just defers to the global handler to do the actual
 * work.
 */
function data_handler($parser, $data) {
	global $handler;
	$handler->data($data);
}

/*
 * SAX parser callback method to handle the end of an element.
 * This method just defers to the global handler to do the actual
 * work.
 */
function end_handler($parser, $name) {
	global $handler;
	$handler->end($name);
}

/*
 * The XmlHandler class is the focal point of the SAX parser callbacks.
 * It keeps track of a stack of element handlers. The element handlers
 * are used to handle whatever elements come in.
 */
class XmlFileHandler {
	var $stack;

	function XmlFileHandler() {
		$this->stack = array ();
	}
	
	function initialize() {
		$element_handler = $this->get_root_element_handler();
		array_push($this->stack, $element_handler);
	}
	
	function get_root_element_handler() {
		return new DoNothingHandler();
	}

	/*
	 * Handle the start callback. Here, we get the current element handler
	 * from the top of the stack and ask it what to do. The element handler
	 * is asked to provide a new handler to handle the new element. That new
	 * handler is put on the top of the stack and will handle all future
	 * callbacks until it is removed (by the end method).
	 */
	function start($name, $attributes) {
		$handler = & array_last($this->stack);
		$next = & $handler->get_next($name, $attributes);
		array_push($this->stack, $next);
	}

	/*
	 * Data has been encountered, send the data to the current element handler
	 * to sort out what needs to be done.
	 */
	function data($data) {
		$handler = & array_last($this->stack);
		$handler->data($data);
	}

	/*
	 * The end of an element has occurred. Pop the current element handler
	 * from the top of the stack and tell it that it's work is done.
	 */
	function end($name) {
		$handler = & array_pop($this->stack);
		$handler->end($name);
	}
}

class XmlElementHandler {
	function & get_next($name, $attributes) {
		$method_name = "get_" . $name . "_handler";
		if (method_exists($this, $method_name)) {
			return $this->$method_name($attributes);
		} else {
			return new DoNothingHandler();
		}
	}

	/*
	 * Ignore data for this element.
	 */
	function data($data) {
	}

	/*
	 * Ignore data for this element.
	 */
	function end($name) {
	}
}

class SimpleTextHandler extends XmlElementHandler {
	var $text;

	function & get_next($name, $attributes) {
		return new DoNothingHandler();
	}

	function data($data) {
		$this->text .= $data;
	}
}

class SimplePropertyHandler extends SimpleTextHandler {
	var $owner;
	var $property;

	function SimplePropertyHandler(& $owner, $property) {
		$this->owner = & $owner;
		$this->property = $property;
	}

	function end($name) {
		$this->set_property_value($this->text);
	}

	function set_property_value(& $value) {
		$property = $this->property;
		$this->owner-> $property = $value;
	}
}

class DoNothingHandler extends XmlElementHandler {
	function & get_next($name, $attributes) {
		return $this;
	}
}

function & array_last(& $array) {
	return $array[count($array) - 1];
}

?>


