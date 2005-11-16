<?php  
    #*****************************************************************************
	#
	# Article.php
	#
	# Author: 		Wayne Beaton
	# Date:			2005-11-07
	#
	# Description: This file defines the Article class which is used to define,
	# and render as html, articles.
	#
	#****************************************************************************
	
	class Article {
		var $title;
		var $link;
		var $abstract;
		var $authors;
		var $categories;
		var $date;
		var $updates;
		
		// Render the article as html.
		function to_html(&$html) {
			$html .= "<b><a href=\"$this->link\">$this->title</a></b>";
				
			// Get the collection of authors and render them.
			$authors = $this->authors_to_html($this->authors);
			if ($authors) {
				$html .= "<br>";
				$html .= $authors;
			}
			if ($this->date) {
				$html .= "<br>";
				$html .= date("F Y", $this->date);
			}
			foreach ($this->updates as $update) {
				$html .= "<br>";
				$update->to_html($html);
			}
			$abstract = $this->abstract;
			$html .= "<blockquote>$abstract</blockquote>";
		}
		
		// Render the article's authors to html.
		private function authors_to_html($authors) {
			$count = count($authors);
			
			if ($count == 0) return;
			
			$html = ' by ';
			$html .= $authors[0]->to_html($html);
			
			// Print each of the authors, excluding the first and the last
			for ($index=1;$index<$count-1;$index++) {
				$html .= ", ";
				$html .= $authors[$index]->to_html($html);
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
				$html .= $authors[$count - 1]->to_html($html);
			}
			
			return $html;
		}
	}
	
	// The Author class represents individual authors of an article.
	class Author {
		var $name;
		var $email;
		var $company;
		var $link;
		
		// Render the Author as html.
		function to_html(&$html) {
			
			// If an email address is provided, include a link.
			if ($this->email) {
				// Screw up the address a bit so that it can't be harvested
				$address = str_replace("@", "_*NOSPAM*_", $this->email);
				$html .= "<a href=\"mailto:$address\">";
			}
			
			// The author name must be provided.
			$html .= $this->name;
			
			// If an email address is provided, then close off the link.
			if ($this->email) {
				$html .= "</a>";
			}
			
			// If company information is provided, display it.
			if ($this->company) {
				$html .= " ($this->company)";
			}
		}
	}
	
?>
