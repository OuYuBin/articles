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
	
	class Category {
		var $id;
		var $title;
		var $description;
	}
	
	class Article {
		var $title;
		var $link;
		var $abstract;
		var $authors;
		var $categories;
		var $date;
		var $update;
	}
	
	class Author {
		var $name;
		var $email;
		var $company;
		var $link;
	}
	
	// This function generates HTML based on the article information provided
	// in the file named $file_name
	function articles_as_html() {
		$file_name = "articles.html";
		
		// First look to see if the html content has been cached.
		// If the cache file exists and it is less than the threadshold
		// (currently 1 month) old, then use it. Otherwise, recompute.
		if (is_file($file_name)) {
			if (filemtime($file_name) > strtotime("-1 month")) {
				$file = fopen($file_name, "r");
				$html = fread($file, filesize($file_name));
				fclose($file);
				
				return $html;
			}
		}
		
		return primitive_articles_as_html();
	}
	
	function compute_and_cache_articles_as_html() {
		$file_name = "articles.html";
		
		$html = primitive_articles_as_html();
	
		// Write the html content to a file cache.
		$file = fopen($file_name, "w");
		fwrite($file, $html);
		fclose($file);
		
	}
		
	function primitive_articles_as_html() {
		$html = '';
		$categories = get_categories();
		$articles = get_articles();		
		articles_to_html($articles, $categories, $html);
				
		return $html;
	}
	
	function articles_to_html($articles, $categories, &$html) {
		foreach($categories as $category) {
			category_to_html($articles, $category, $html);
		} 
	}
	
	function category_to_html($articles, $category, &$html) {
		$html .= "<h3>$category->title</h3>";
		if ($category->description) {
			$html .= "<p>$category->description</p>";
		}
		
		if (strcmp($category->id, "recent") == 0) {			
			// If the category has id "recent", then show
			// only the most recent (i.e. date or update in the last six months)
			// articles.
			$base_date = strtotime("-6 month");
			foreach ($articles as $article) {
				if (($article->date > $base_date) or ($article->update > $base_date)) {
					article_to_html($article, $html);
				}
			}
		} else {
			// Otherwise, show only the articles that fall in
			// the category.
			foreach ($articles as $article) {
				if (in_array($category->id, $article->categories)) {
					article_to_html($article, $html);
				}
			}
		}
	}
	
	function article_to_html($article, &$html) {
		$html .= "<b><a href=\"$article->link\">$article->title</a></b><br>";
			
		// Get the collection of authors and render them.
		authors_to_html($article->authors, $html);
		$html .= date("F Y", $article->date);
		if ($article->update) {
			$html .= " (last updated ";
			$html .= date("F Y", $article->update);
			$html .= ")";
		}
		$abstract = $article->abstract;
		$html .= "<blockquote>$abstract</blockquote>";
	}
	
	function authors_to_html($authors, &$html) {
		$count = count($authors);
		
		// If there is at least one author, print their information
		if ($count > 0) {
			$html .= ' by ';
			$html .= author_to_html($authors[0], $html);
		}
		
		// Print each of the authors, excluding the first and the last
		for ($index=1;$index<$count-1;$index++) {
			$html .= ", ";
			$html .= author_to_html($authors[$index], $html);
		}
		
		// If there are more than two authors, then we need to separate
		// the last one from the second last one with a comma.
		if ($count > 2) {
			$html .= ",";
		}
		
		// If there was more than one author, then print the last author's
		// information
		if ($count > 1) {
			$html .= " and ";
			$html .= author_to_html($authors[$count - 1], $html);
		}
		
		if ($count > 0) {
			$html .= "<br>";
		}
	}
	
	function author_to_html($author, &$html) {
		
		// If an email address is provided, include a link.
		if ($author->email) {
			// Screw up the address a bit so that it can't be harvested
			$address = str_replace("@", "_*NOSPAM*_", $author->email);
			$html .= "<a href=\"mailto:$address\">";
		}
		
		// The author name must be provided.
		$html .= $author->name;
		
		// If an email address is provided, then close off the link.
		if ($author->email) {
			$html .= "</a>";
		}
		
		// If company information is provided, display it.
		if ($author->company) {
			$html .= " ($author->company)";
		}
	}
	
	function get_categories() {
		$xml = simplexml_load_file("categories.xml");
		$categories = array();
		foreach($xml->category as $category) {
			$new_category = new Category();
			$new_category->id = trim($category[id]);
			$new_category->title = $category->title;
			$new_category->description = $category->description;
			array_push($categories, $new_category);
		}
		return $categories;
	}
	
	function get_articles() {
		$articles = array();
		$dh = opendir(".");
       	while (($dir = readdir($dh)) !== false) {
       		if (is_dir($dir)) {
       			$file = $dir . DIRECTORY_SEPARATOR . "about.xml";
       			if (is_file($file)) {
       				$xml = simplexml_load_file($file);
       				$article = xml_to_article($xml, $dir);
       				array_push($articles, $article);
       			}
       		}
       	}
		closedir($dh);
		
		return $articles;
	}
	
	function xml_to_article(&$xml, &$root) {		
		$new_article = new Article();
		$new_article->title = $xml->title;
		$new_article->link = $root . DIRECTORY_SEPARATOR . $xml[link];
		$new_article->abstract = $xml->abstract;
		$new_article->date = strtotime($xml->date);	
		$update = $xml->update;
		if (strlen($update) > 0) {	
			$new_article->update = strtotime($xml->update);
		}
		$new_article->authors = get_article_authors($xml);
		$new_article->categories = get_article_categories($xml);
		
		return $new_article;
	}
	
	function get_article_authors(&$article) {
		$authors = array();
		foreach($article->author as $author) {
			$new_author = new Author();
			$new_author->name = $author[name];
			$new_author->email = $author[email];
			$new_author->company = $author[company];
			$new_author->link = $author[link];
			
			array_push($authors, $new_author);
		}
		return $authors;
	}	
	
	function get_article_categories(&$article) {
		$categories = array();
		foreach ($article->category as $category) {
			array_push($categories, trim($category));
		}
		return $categories;
	}
	
?>
