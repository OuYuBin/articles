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
	
	include("Article.php");
	include("Category.php");
	include("Update.php");
	
	class ArticleListing {
		var $categories = array();
	
		function add_category(&$category) {
			$id = $category->id;
			$this->categories = array_merge($this->categories, array($id => $category));
		}
		
		function add_article(&$article) {
			foreach ($article->categories as $category_id) {
				$category = $this->categories[$category_id];
				if (is_object($category)) {
					$category->add_article($article);
				}
			}
			
			$this->add_recent_article($article);
		}
		
		private function add_recent_article(&$article) {	
			// If there is no "recent" category, bail out.
			$category = $this->categories["recent"];		
			if (!is_object(category)) return;
			
			// I consider recent to mean changed within the
			// last six months...
			$base_date = strtotime("-6 month");
			if (($article->date > $base_date) or ($article->update > $base_date)) {
				$this->categories["recent"]->add_article($article);
			}			
		}
		
		function to_html(&$html) {
			$html .= "<h3>Contents</h3>";
			$html .= "<ul class=\"midlist\">";
			foreach($this->categories as $category) {
				if ($category->has_articles()) {
					$html .= "<li><a href=\"#$category->id\">$category->title</a></li>";
				}
			}
			$html .= "</ul>";
			foreach($this->categories as $category) {
				$category->to_html($html);
			} 
		}
	}
	
?>
