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

// This function generates HTML based on the article information provided
// in the file named $file_name
function articles_as_html() {
	$html = "";
	$listing = xml_to_ArticleListing();
	$listing->to_html(& $html);
	return $html;
}

function xml_to_ArticleListing() {
	$listing = new ArticleListing();
	get_categories($listing);
	get_articles($listing);
	return $listing;
}

function get_categories(& $listing) {
	global $handler;
	$handler = new CategoryXmlHandler($listing);

	parse_xml_file("categories.xml");
}

function get_articles(& $listing) {
	$dh = opendir(".");
	while (($dir = readdir($dh)) !== false) {
		if (is_dir($dir)) {
			$file_name = $dir.DIRECTORY_SEPARATOR."about.xml";
			if (is_file($file_name)) {
				$article = get_article($file_name);
				$listing->add_article($article);
			}
		}
	}
	closedir($dh);
}

function get_article($file_name) {
	global $handler;
	$handler = new ArticleXmlHandler();

	parse_xml_file($file_name);
	return $handler->article;
}

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
function parse_xml_file($file_name) {
	// Read the entire file contents in to memory.
	// If file sizes get too large, we'll have to be smarter here.
	$file = fopen($file_name, "r");
	$xml = fread($file, filesize($file_name));
	fclose($file);

	$parser = xml_parser_create();
	xml_set_element_handler($parser, 'startHandler', 'endHandler');
	xml_set_character_data_handler($parser, 'dataHandler');
	xml_parse($parser, $xml);
}

/*
 * The $handler variable is global. It manages all the call backs
 * that come (indirectly) from the SAX parser.
 */
$handler;

/*
 * SAX parser callback method to handle the start of an element.
 * This method just defers to the global handler to do the actual
 * work.
 */
function startHandler($parser, $name, $attributes) {
	global $handler;
	$handler->start($name, $attributes);
}

/*
 * SAX parser callback method to handle the text for an element.
 * This method just defers to the global handler to do the actual
 * work.
 */
function dataHandler($parser, $data) {
	global $handler;
	$handler->data($data);
}

/*
 * SAX parser callback method to handle the end of an element.
 * This method just defers to the global handler to do the actual
 * work.
 */
function endHandler($parser, $name) {
	global $handler;
	$handler->end($name);
}

/*
 * The XmlHandler class is the focal point of the SAX parser callbacks.
 * It keeps track of a stack of element handlers. The element handlers
 * are used to handle whatever elements come in.
 */
class XmlHandler {
	var $stack;

	function XmlHandler() {
		$this->stack = array ();
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

class CategoryXmlHandler extends XmlHandler {
	var $listing;

	function CategoryXmlHandler(& $listing) {
		parent :: XmlHandler();
		$this->listing = & $listing;
		array_push($this->stack, new CategoryRootHandler($this->listing));
	}
}

/*
 * The FeedHandler class takes care of the root element in the file.
 */
class CategoryRootHandler {
	var $listing;

	function CategoryRootHandler(& $listing) {
		$this->listing = & $listing;
	}

	/*
	 * Return an appropriate handler to deal with an element named $name.
	 * At this point, we only expect to see an <rss> element.
	 */
	function & get_next($name, $attributes) {
		if (strcasecmp($name, "categories") == 0) {
			return new CategoriesHandler($this->listing);
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

/*
 * The FeedHandler class takes care of the root element in the file.
 */
class CategoriesHandler {
	var $listing;

	function CategoriesHandler(& $listing) {
		$this->listing = & $listing;
	}

	/*
	 * Return an appropriate handler to deal with an element named $name.
	 * At this point, we only expect to see an <rss> element.
	 */
	function & get_next($name, & $attributes) {
		if (strcasecmp($name, "category") == 0) {
			return new CategoryHandler($this->listing, $attributes);
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

class CategoryHandler {
	var $listing;
	var $category;

	function CategoryHandler(& $listing, & $attributes) {
		$this->category = new Category();
		$this->category->id = $attributes['ID'];
		$this->listing = & $listing;
	}

	function data($data) {
	}

	function & get_next($name, $attributes) {
		if (strcasecmp($name, "title") == 0) {
			return new SimplePropertyHandler($this->category, "title");
		} else
			if (strcasecmp($name, "description") == 0) {
				return new SimplePropertyHandler($this->category, "description");
			} else {
				return new DoNothingHandler();
			}
	}

	function end($name) {
		$title = $this->category->title;
		$this->listing->add_category($this->category);
	}
}

class ArticleXmlHandler extends XmlHandler {
	var $article;

	function ArticleXmlHandler() {
		parent :: XmlHandler();
		$this->article = new Article();
		array_push($this->stack, new ArticleRootHandler($this->article));
	}
}

/*
 * The FeedHandler class takes care of the root element in the file.
 */
class ArticleRootHandler {
	var $article;

	function ArticleRootHandler(& $article) {
		$this->article = & $article;
	}

	function & get_next($name, $attributes) {
		if (strcasecmp($name, "article") == 0) {
			return new ArticleHandler($this->article, $attributes);
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

class ArticleHandler {
	var $article;

	function ArticleHandler(& $article, & $attributes) {
		$this->article = & $article;
		$this->article->link = $attributes['LINK'];
		$this->article->categories = array ();
		$this->article->updates = array ();
		$this->article->authors = array ();
	}

	function data($data) {
	}

	function & get_next($name, $attributes) {
		if (strcasecmp($name, "title") == 0) {
			return new SimplePropertyHandler($this->article, "title");
		} else
			if (strcasecmp($name, "date") == 0) {
				return new SimplePropertyHandler($this->article, "date");
			} else
				if (strcasecmp($name, "abstract") == 0) {
					return new SimplePropertyHandler($this->article, "abstract");
				} else
					if (strcasecmp($name, "category") == 0) {
						return new ArticleCategoryHandler($this->article);
					} else
						if (strcasecmp($name, "author") == 0) {
							return new AuthorHandler($this->article);
						} else
							if (strcasecmp($name, "update") == 0) {
								return new UpdateHandler($this->article, $attributes);
							} else {
								return new DoNothingHandler();
							}
	}

	function end($name) {
		if ($this->article->date)
			$this->article->date = strtotime($this->article->date);
	}
}

class ArticleCategoryHandler extends SimpleTextHandler {
	var $article;

	function ArticleCategoryHandler(& $article) {
		$this->article = & $article;
	}

	function end($name) {
		array_push($this->article->categories, $this->text);
	}
}

class AuthorHandler {
	var $owner;
	var $author;

	function AuthorHandler(& $owner) {
		$this->owner = & $owner;
		$this->author = new Author();
	}

	function data($data) {
	}

	function & get_next($name, $attributes) {
		if (strcasecmp($name, "name") == 0) {
			return new SimplePropertyHandler($this->author, "name");
		} else
			if (strcasecmp($name, "company") == 0) {
				return new SimplePropertyHandler($this->author, "company");
			} else
				if (strcasecmp($name, "email") == 0) {
					return new SimplePropertyHandler($this->author, "email");
				} else
					if (strcasecmp($name, "link") == 0) {
						return new SimplePropertyHandler($this->author, "link");
					} else {
						return new DoNothingHandler();
					}
	}

	function end($name) {
		array_push($this->owner->authors, $this->author);
	}
}

class UpdateHandler {
	var $owner;
	var $update;

	function UpdateHandler(& $owner, & $attributes) {
		$this->owner = & $owner;
		$this->update = new Update();
		$this->update->date = strtotime($attributes['DATE']);
		$this->update->authors = array ();
	}

	function data($data) {
	}

	function & get_next($name, $attributes) {
		if (strcasecmp($name, "author") == 0) {
			return new AuthorHandler($this->update);
		} else {
			return new DoNothingHandler();
		}
	}

	function end($name) {
		array_push($this->owner->updates, $this->update);
	}
}

class SimpleTextHandler {
	var $text;

	function & get_next($name, $attributes) {
		return new DoNothingHandler();
	}

	function data($data) {
		$this->text .= $data;
	}

	function end($name) {
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

class DoNothingHandler {

	function DoNothingHandler() {
	}

	function data($data) {
	}

	function end($name) {
	}

	function & get_next($name, $attributes) {
		return $this;
	}
}

function & array_last(& $array) {
	return $array[count($array) - 1];
}

// PHP 5 Code...

//	function get_categories(&$listing) {
//		$xml = simplexml_load_file("categories.xml");
//		$categories = array();
//		foreach($xml->category as $category) {
//			$new_category = new Category();
//			$new_category->id = trim($category[id]);
//			$new_category->title = $category->title;
//			$new_category->description = $category->description;
//			$listing->add_category($new_category);
//		}
//	}
//	
//	function get_articles(&$listing) {
//		$dh = opendir(".");
//       	while (($dir = readdir($dh)) !== false) {
//       		if (is_dir($dir)) {
//       			$file = $dir . DIRECTORY_SEPARATOR . "about.xml";
//       			if (is_file($file)) {
//       				$xml = simplexml_load_file($file);
//       				$article = xml_to_article($xml, $dir);
//       				$listing->add_article($article);
//       			}
//       		}
//       	}
//		closedir($dh);
//	}
//	
//	function xml_to_article(&$xml, &$root) {		
//		$new_article = new Article();
//		$new_article->title = $xml->title;
//		$new_article->link = $root . "/" . $xml[link];
//		$new_article->abstract = $xml->abstract;
//		if (strlen($xml->date) > 0) $new_article->date = strtotime($xml->date);	
//		$new_article->updates = get_article_updates($xml);
//		$new_article->authors = get_article_authors($xml);
//		$new_article->categories = get_article_categories($xml);
//				
//		return $new_article;
//	}
//	
//	function get_article_updates(&$xml) {
//		$updates = array();
//		foreach ($xml->update as $update) {
//			$new_update = new Update();
//			if ($update[date]) $new_update->date = strtotime($update[date]);
//			$new_update->authors = get_article_authors($update);
//			$new_update->reason = trim($update->reason);
//			array_push($updates, $new_update);
//		}
//		return $updates;
//	}
//	
//	function get_article_authors(&$article) {
//		$authors = array();
//		foreach($article->author as $author) {
//			$new_author = new Author();
//			// HACK. For some reason the &egrave; character encoding doesn't read from XML...
//			$new_author->name = str_replace("[egrave]", "&egrave;", $author[name]);
//			$new_author->email = $author[email];
//			$new_author->company = $author[company];
//			$new_author->link = $author[link];
//			
//			array_push($authors, $new_author);
//		}
//		return $authors;
//	}	
//	
//	function get_article_categories(&$article) {
//		$categories = array();
//		foreach ($article->category as $category) {
//			array_push($categories, trim($category));
//		}
//		return $categories;
//	}
//	
?>


