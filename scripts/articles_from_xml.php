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
	
	include("ArticleListing.php");
	
	// This function generates HTML based on the article information provided
	// in the file named $file_name
	function articles_as_html() {
		$file_name = "articles.html";
		
		// First look to see if the html content has been cached.
		// If the cache file exists and it is less than the threadshold
		// (currently 3 months) old, then use it. Otherwise, recompute.
		if (is_file($file_name)) {
			if (filemtime($file_name) > strtotime("-3 month")) {
				$file = fopen($file_name, "r");
				$html = fread($file, filesize($file_name));
				fclose($file);
				
				return $html;
			}
		}
				
		$html = "";
		xml_to_ArticleListing()->to_html(&$html);
		return $html;
	}
	
	function compute_and_cache_articles_as_html() {
		$file_name = "articles.html";
		
		$html = "";
		xml_to_ArticleListing()->to_html(&$html);
		
		// Write the html content to a file cache.
		$file = fopen($file_name, "w");
		fwrite($file, $html);
		fclose($file);
		
	}
		
	function xml_to_ArticleListing() {
		$listing = new ArticleListing();
		get_categories($listing);
		get_articles($listing);						
		return $listing;
	}
		
	function get_categories(&$listing) {
		$xml = simplexml_load_file("categories.xml");
		$categories = array();
		foreach($xml->category as $category) {
			$new_category = new Category();
			$new_category->id = trim($category[id]);
			$new_category->title = $category->title;
			$new_category->description = $category->description;
			$listing->add_category($new_category);
		}
	}
	
	function get_articles(&$listing) {
		$dh = opendir(".");
       	while (($dir = readdir($dh)) !== false) {
       		if (is_dir($dir)) {
       			$file = $dir . DIRECTORY_SEPARATOR . "about.xml";
       			if (is_file($file)) {
       				$xml = simplexml_load_file($file);
       				$article = xml_to_article($xml, $dir);
       				$listing->add_article($article);
       			}
       		}
       	}
		closedir($dh);
	}
	
	function xml_to_article(&$xml, &$root) {		
		$new_article = new Article();
		$new_article->title = $xml->title;
		$new_article->link = $root . "/" . $xml[link];
		$new_article->abstract = $xml->abstract;
		if (strlen($xml->date) > 0) $new_article->date = strtotime($xml->date);	
		$new_article->updates = get_article_updates($xml);
		$new_article->authors = get_article_authors($xml);
		$new_article->categories = get_article_categories($xml);
				
		return $new_article;
	}
	
	function get_article_updates(&$xml) {
		$updates = array();
		foreach ($xml->update as $update) {
			$new_update = new Update();
			if ($update[date]) $new_update->date = strtotime($update[date]);
			$new_update->authors = get_article_authors($update);
			$new_update->reason = trim($update->reason);
			array_push($updates, $new_update);
		}
		return $updates;
	}
	
	function get_article_authors(&$article) {
		$authors = array();
		foreach($article->author as $author) {
			$new_author = new Author();
			// HACK. For some reason the &egrave; character encoding doesn't read from XML...
			$new_author->name = str_replace("[egrave]", "&egrave;", $author[name]);
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
