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
	
	function abstracts_to_html($file_name) {
		$stuff = simplexml_load_file($file_name);
		$html = '';
		
		foreach($stuff->article as $article) {	
			$html .= abstract_to_html($article);
		} 

		return $html;
	}
	
	function abstract_to_html($article) {
			$html .= "<b><a href=\"$article[link]\">$article->title</a></b><br>";
			
			// Get the collection of authors and render them.
			$html .= authors_to_html($article->xpath('./author'));
			$html .= "<br>";
			$html .= $article->date;
			if (strlen($article->update) > 0) {
				$html .= " (last updated ";
				$html .= $article->update;
				$html .= ")";
			}
			$abstract = $article->abstract->asXML();
			$html .= "<blockquote>$abstract</blockquote>";
			
			return $html;		
	}
	
	function authors_to_html($authors) {
		$html = '';
		$count = count($authors);
		
		// If there is at least one author, print their information
		if ($count > 0) {
			$html .= ' by ';
			$html .= author_to_html($authors[0]);
		}
		
		// Print each of the authors, excluding the first and the last
		for ($index=1;$index<$count-1;$index++) {
			$html .= ", ";
			$html .= author_to_html($authors[$index]);
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
			$html .= author_to_html($authors[$count - 1]);
		}
		
		return $html;
	}
	
	function author_to_html($author) {
		$html = '';
		
		// If an email address is provided, include a link.
		if ($author[email]) {
			// Screw up the address a bit so that it can't be harvested
			$address = str_replace("@", "_*NOSPAM*_", $author[email]);
			$html .= "<a href=\"mailto:$address\">";
		}
		
		// The author name must be provided.
		$html .= $author[name];
		
		// If an email address is provided, then close off the link.
		if ($author[email]) {
			$html .= "</a>";
		}
		
		// If company information is provided, display it.
		if ($author[company]) {
			$html .= " ($author[company])";
		}
		return $html;
	}
?>
