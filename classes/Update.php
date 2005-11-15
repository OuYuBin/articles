<?php  
    #*****************************************************************************
	#
	# Update.php
	#
	# Author: 		Wayne Beaton
	# Date:			2005-11-07
	#
	# Description: This file defines the Update class which is used to define,
	# and render as html, information about updates to articles.
	#
	#****************************************************************************
	
	class Update {
		var $date;
		var $authors;
		var $reason;
		
		// Render the article as html.
		function to_html(&$html) {
			$html .= "Updated ";	
			$html .= date("F Y", $this->date);
			// Get the collection of authors and render them.
			$this->authors_to_html($this->authors, $html);
			if (strlen($this->reason)) {
				$html .= " $this->reason";
			}
		}
		
		// Render the article's authors to html.
		private function authors_to_html($authors, &$html) {
			$count = count($authors);
			
			// If there is at least one author, print their information
			if ($count > 0) {
				$html .= ' by ';
				$html .= $authors[0]->to_html($html);
			}
			
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
		}
	}
?>
