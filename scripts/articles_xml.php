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
include($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/xml_sax_parsing.php");

/*
 * This global variable defines the location of the 'articles' root directory.
 * The metadata for the articles is located in this directory. All articles
 * also hang off this directory.
 */
$articles_root = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "articles" . DIRECTORY_SEPARATOR;
/*
 * This function loads categories into the provided ArticleListing instance.
 * Category information is found in "$articles_root/categories.xml" in XML
 * format.
 */
function load_categories(& $listing) {
	$categories_file = $GLOBALS['articles_root'] . "categories.xml";
	$handler = new CategoryFileHandler($listing);
	parse_xml_file($categories_file, $handler);
}

/*
 * This function loads all the accessible articles into the provided
 * ArticleListing instance. All articles are assumed to be in directories
 * hanging off $article_root and the directory must contain an about.xml
 * file. Only those directories with an about.xml file are considered; all
 * others are ignored.
 * 
 * Each of the about.xml files are loaded and information about the article
 * is parsed out into instances of the Article class, and placed into the
 * given ArticleListing instance.
 */
function load_articles(& $listing) {
	$dh = opendir($GLOBALS['articles_root']);
	while (($dir = readdir($dh)) !== false) {
		if (is_dir($dir)) {
			$file_name = $dir.DIRECTORY_SEPARATOR."about.xml";
			if (is_file($file_name)) {
				$article = get_article($dir, $file_name);
				$listing->add_article($article);
			}
		}
	}
	closedir($dh);
}

/*
 * This function answers an instance of Article containing the
 * information represented in $file_name. $file_name is assumed to
 * be a file name with a complete path to an XML file conforming
 * to the about.xml structure. $root is the directory that contains
 * the actual content of the article (generally this is the same
 * as the path for $file_name).
 */
function & get_article($root, $file_name) {
	$handler = new ArticleFileHandler($root);
	parse_xml_file($file_name, $handler);
	return $handler->article;
}

/*
 * This class is used by the SAX parser to start parsing
 * a category file.
 */
class CategoryFileHandler extends XmlFileHandler {
	var $listing;

	function CategoryFileHandler(& $listing) {
		parent :: XmlFileHandler();
		$this->listing = & $listing;
	}

	/*
	 * This method returns the root handler for a category file
	 * The root handler essentially represents the file itself
	 * rather than any actual element in the file. The returned
	 * element handler will deal with any elements that may occur
	 * in the root of the XML file.
	 */
	function get_root_element_handler() {
		return new CategoryRootHandler($this->listing);
	}	
}

/*
 * The CategoryRootHandler class takes care of the root element 
 * in the file. This handler doesn't correspond to any particular
 * element that may occur in the XML file. It represents the file
 * itself and must deal with any elements that occur at the root
 * level in that file.
 */
class CategoryRootHandler extends XmlElementHandler {
	var $listing;

	function CategoryRootHandler(& $listing) {
		$this->listing = & $listing;
	}

	/*
	 * This method handles the <categories>...</categories> element.
	 */
	function & get_categories_handler($attributes) {
		return new CategoriesHandler($this->listing);
	}
}

/*
 * The CategoriesHandler takes care of the <categories> element. This
 * element is the root element in the file and is used to contain
 * all the categories.
 * 
 * <categories>
 * 		<category>...</category>
 * 		<category>...</category>
 * 		<category>...</category>
 * </categories>
 */
class CategoriesHandler extends XmlElementHandler {
	var $listing;

	function CategoriesHandler(& $listing) {
		$this->listing = & $listing;
	}

	/*
	 * This method handles the <category>...</category> element.
	 */
	function & get_category_handler(& $attributes) {
		return new CategoryHandler($this->listing, $attributes);
	}
	
	/*
	 * When the category handler is done gathering information about
	 * the category, add the category to the listing.
	 */
	function end_category_handler(& $handler) {
		$this->listing->add_category($handler->category);
	}
}
/*
 * The CategoryHandler takes care of the <category> element. This
 * element may occur multiple times inside the <categories> tag.
 * 
 * <categories>
 * 		<category id="...">
 * 			<title>...</title>
 * 			<description>...</description>
 * 		</category>
 * 		<category>...</categories>
 * 		<category>...</categories>
 * </categories>
 */
class CategoryHandler extends XmlElementHandler {
	var $category;

	function CategoryHandler(& $listing, & $attributes) {
		$this->category = new Category();
		// The id is represented in an attribute.
		$this->category->id = $attributes['ID'];
	}
	
	/*
	 * This method handles the <title>...</title> element.
	 */
	function & get_title_handler($attributes) {
		return new SimplePropertyHandler($this->category, "title");
	}
	
	/*
	 * This method handles the <description>...</description> element.
	 */
	function & get_description_handler($attributes) {
		return new SimplePropertyHandler($this->category, "description");
	}
}

class ArticleFileHandler extends XmlFileHandler {
	var $article;

	function ArticleFileHandler($root) {
		parent :: XmlFileHandler();
		$this->article = new Article();
		$this->article->root = $root;
	}
	

	function get_root_element_handler() {
		return new ArticleRootHandler($this->article);
	}	
}

/*
 * The FeedHandler class takes care of the root element in the file.
 */
class ArticleRootHandler extends XmlElementHandler {
	var $article;
	var $root;

	function ArticleRootHandler(& $article) {
		$this->article = & $article;
	}

	function & get_article_handler($attributes) {
		return new ArticleHandler($this->article, $attributes);
	}
}

class ArticleHandler extends XmlElementHandler {
	var $article;

	function ArticleHandler(& $article, & $attributes) {
		$this->article = & $article;
		$this->article->link = $attributes['LINK'];
	}

	function & get_title_handler($attributes) {
		return new SimplePropertyHandler($this->article, "title");
	}
	
	function & get_date_handler($attributes) {
		return new SimplePropertyHandler($this->article, "date");
	}
	
	function & get_abstract_handler($attributes) {
		return new SimplePropertyHandler($this->article, "abstract");
	}
	
	function & get_category_handler($attributes) {
		return new ArticleCategoryHandler($this->article);
	}
	
	function &get_author_handler($attributes) {
		return new AuthorHandler($this->article);
	}
	
	function end_author_handler($handler) {
		$this->article->add_author($handler->author);
	}
	
	function & get_update_handler($attributes) {
		return new UpdateHandler($attributes);
	}
	
	function end_update_handler(&$handler) {
		$this->article->add_update($handler->update);
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

class AuthorHandler extends XmlElementHandler {
	var $owner;
	var $author;

	function AuthorHandler(& $owner) {
		$this->owner = & $owner;
		$this->author = new Author();
	}

	function & get_name_handler($attributes) {
		return new SimplePropertyHandler($this->author, "name");
	}
	
	function & get_company_handler($attributes) {
		return new SimplePropertyHandler($this->author, "company");
	}
	
	function & get_email_handler($attributes) {
		return new SimplePropertyHandler($this->author, "email");
	}
	
	function & get_link_handler($attributes) {
		return new SimplePropertyHandler($this->author, "link");
	}

//	function end($name) {
//		array_push($this->owner->authors, $this->author);
//	}
}

class UpdateHandler extends XmlElementHandler{
	var $update;

	function UpdateHandler(& $attributes) {
		$this->update = new Update();
		$this->update->date = strtotime($attributes['DATE']);
	}

	function & get_author_handler($attributes) {
		return new AuthorHandler($this->update);
	}
	
	function end_author_handler($handler) {
		$this->update->add_author($handler->author);
	}
	
	function & get_reason_handler($attributes) {
		return new SimplePropertyHandler($this->update, "reason");
	}
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


