<?php  
    #*****************************************************************************
	#
	# Category.php
	#
	# Author: 		Wayne Beaton
	# Date:			2005-11-07
	#
	# Description: This file defines the Category class. This class is used
	# to represent, and render as html, Categories. 
	#
	#****************************************************************************
	
	class Category {
		var $id;
		var $title;
		var $description;
		var $articles = array();
	
		// Render this category in html format.
		function to_html(&$html) {
			if (!$this->has_articles()) return;
			$html .= "<div class=\"homeitem3col\">";
			$html .= "<h3><a name=\"$this->id\">$this->title</a></h3>";
			if ($this->description) {
				$html .= "<p>$this->description</p>";
			}

			foreach ($this->articles as $article) {
				$article->to_html($html);
			}
			$html .= "</div>";
		}
		
		// Add an article to the category.
		function add_article(&$article) {
			array_push($this->articles, $article);
		}
		
		// Does this category have any articles?
		function has_articles() {
			return count($this->articles) > 0;
		}
	}
	
?>
