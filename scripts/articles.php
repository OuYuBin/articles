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
require_once("articles_xml.php");

/*
 * Provided with the id of a category (if not specified, 'all' is used), 
 * this method generates the html to render information about the articles
 * in that category.
 */
function get_articles_as_html($category_id = 'all') {
	$html = "";
	$listing = get_listing();
	$category = $listing->categories[$category_id]; 
	if (!$category)
		$category = $listing->categories['all'];
	$category->to_html($html);
	return $html;
}

function get_recent_articles_summary($count) {
	$listing = get_listing();
	$category = $listing->categories['all'];
	$category->sort_articles();
	$html = "";
	foreach($category->articles as $article) {
		if ($count <= 0) break;		
		$authors = $article->authors_to_html();

		$short_description = full_word_substr($article->description);
		
		$html .= "<li>";
		$html .= "<a target=\"_blank\" href=\"$article->root/$article->link\">$article->title</a> $authors";
		$html .= "<br />$short_description";
		$html .= "</li>";
		$count--;
	}
	return $html;
}

/*
 * This function returns a substring of the contents of $text that
 * is at most $max characters in length containing only full words.
 * That is, the break will occur on whitespace.
 */
function full_word_substr($text, $max=120) {
	while ($max > 0 && substr($text, $max, 1) != ' ') $max--;
	return substr($text, 0, $max) . '...';
}

/*
 * This method generates an html list of all categories. The intent is to
 * let users click an entry from this list to filter the set of articles
 * displayed. Each item in the generated list contains a link back to the
 * articles page (specified using the $base_url parameter); the link is
 * parameterized with the id of the category to filter on. No link is 
 * included on the category with id $category_id as this category is
 * assumed to be displayed currently.
 */
function get_categories_for_filtering_as_html($base_url, $category_id) {
	$html = "";
	$categories = get_categories();
	foreach ($categories as $category) {
		$html .= "<li>";
		if ($category_id != $category->id)
			$html .= "<a href=\"$base_url?filter=$category->id\">";
		$html .="$category->title";
		if ($category_id != $category->id)
			$html .= "</a>";
		$html .= "</li>";
	}
	return $html;
}

/*
 * This function returns a list of Category instances representing the
 * categories under consideration.
 */
function & get_categories() {
	$listing = new ArticleListing();
	load_categories($listing);
	return $listing->categories;
}

/*
 * This function runs an instance of ArticleListing containing all the
 * information we know about articles.
 */
function get_listing() {
	$listing = new ArticleListing();
	load_categories($listing);
	load_articles($listing);
	return $listing;
}

/*
 * The ArticleListing class represents all the categories and articles
 * that our current universe knows about (restricted to Eclipse Corner,
 * of course).
 */
class ArticleListing {
	var $categories = array ();

	function add_category(& $category) {
		$id = $category->id;
		$this->categories = array_merge($this->categories, array ($id => $category));
	}

	function get_category($category_id) {
		return $categories[$category_id];
	}

	function add_article(& $article) {
		if (!$article->show) return;
		foreach ($article->categories as $category_id) {
			$category = $this->categories[$category_id];
			if ($category) {
				// TODO Yucky work-around. For some reason =& isn't working... investigate
				$this->categories[$category_id]->add_article($article);
			}
		}

		$this->add_recent_article($article);
		$this->categories["all"]->add_article($article);
	}

	function add_recent_article(& $article) {
		// If there is no "recent" category, bail out.
		$category = & $this->categories["recent"];
		if (!is_object($category))
			return;
			
		// I consider recent to mean created or changed within the
		// last year...
		$base_date = strtotime("-1 year");
		if ($article->is_more_recent_than($base_date)) {
			$category->add_article($article);
		}
	}

	function to_html(& $html) {
		$html .= "<h3>Contents</h3>";
		$html .= "<ul class=\"midlist\">";
		foreach ($this->categories as $category) {
			if ($category->has_articles()) {
				$html .= "<li><a href=\"#$category->id\">$category->title</a></li>";
			}
		}
		$html .= "</ul>";
		foreach ($this->categories as $category) {
			$category->to_html($html);
		}
	}
}

class Category {
	var $id;
	var $title;
	var $description;
	var $articles = array ();

	// Render this category in html format.
	function to_html(& $html) {
		$this->sort_articles();
		$html .= "<h3><a href=\"/articles/articles.rss?filter=$this->id\"><img src=\"/images/rss.gif\" title=\"RSS feed for $this->title\" alt=\"[RSS]\" align=\"right\"/></a><a name=\"$this->id\">$this->title</a></h3>";

		if ($this->has_articles()) {
			if ($this->description) {
				$html .= "<p>$this->description</p>";
			}
			$html .= "<ul class=\"midlist\">";
			foreach ($this->articles as $article) {
				$article->to_html($html);
			}
			$html .= "</ul>";
		} else {
			$html .= 'This category has no articles.';
		}
	}

	function sort_articles() {
		usort($this->articles, 'sort_articles_cmp');

	}

	// Add an article to the category.
	function add_article(& $article) {
		array_push($this->articles, $article);
	}

	// Does this category have any articles?
	function has_articles() {
		return count($this->articles) > 0;
	}
}

function sort_articles_cmp($a, $b) {
	$a_date = get_last_update_date($a);	
	$b_date = get_last_update_date($b);
	if ($a_date == $b_date)
		return 0;
	return $a_date < $b_date ? 1 : -1;
}

function get_last_update_date($article) {
	$updates = $article->updates;
	if (count($updates) > 0) {
		$update = $updates[count($updates) - 1];
		return $update->date;
	}
	return $article->date;
}

class Article {
	var $title;
	var $root;
	var $link;
	var $translations = array();
	var $description;
	var $authors = array();
	var $categories = array();
	var $date;
	var $updates = array();
	var $show = true;

	function add_translation(&$translation) {
		$translation->root = $this->root;
		array_push($this->translations, $translation);
	}
	
	function add_author(&$author) {
		array_push($this->authors, $author);
	}
	
	function add_update(&$update) {
		array_push($this->updates, $update);
	}

	function is_more_recent_than($date) {
		if ($this->date > $date) return true;
		foreach ($this->updates as $update) {
			if ($update->date > $date) return true;
		}
		return false;
	}
	
	function most_recent_date() {
		$date = $this->date;
		foreach ($this->updates as $update) {
			if ($update->date > $date)
				$date = $update->date;
		}
		return $date;
	}
	
	// Render the article as html.
	function to_html(& $html) {
		$html .= "<li><a target=\"_blank\" href=\"$this->root/$this->link\">$this->title</a>";

		// Get the collection of authors and render them.
		$authors = $this->authors_to_html();
		if ($authors) {
			$html .= "<br>";
			$html .= $authors;
		}
		if ($this->date) {
			$html .= "<br />";
			$html .= date("F Y", $this->date);
		}
		$update_count = count($this->updates);
		if ($update_count > 0) {
			$update = $this->updates[$update_count - 1];
			$html .= '<br>';
			$update->to_html($html);
		}
		
		foreach ($this->translations as $translation) {
			$html .= "<br>";
			$translation->to_html($html);
		}
		
		$html .= "<blockquote>";
		$html .= $this->description;	
		$html .="</blockquote>";
	}

	// Render the article's authors to html.
	function authors_to_html() {
		$authors = $this->authors;
		$count = count($authors);

		if ($count == 0)
			return;

		$html = ' by ';
		$html .= $authors[0]->to_html($html);

		// Print each of the authors, excluding the first and the last
		for ($index = 1; $index < $count -1; $index ++) {
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
			$html .= $authors[$count -1]->to_html($html);
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
	function to_html(& $html) {

		// If an email address is provided, include a link.
		if ($this->email) {
			// Screw up the address a bit so that it can't be harvested
			$address = str_replace("@", "_*NOSPAM*_", $this->email);
			$html .= "<a href=\"mailto:$address\">";
		}

		// The author name must be provided.
		$html .= str_replace(' ', '&nbsp;', $this->name);

		// If an email address is provided, then close off the link.
		if ($this->email) {
			$html .= "</a>";
		}

		// If a link is provided, display it.
		if ($this->link) {
			$html .= "<a href=\"$this->link\" target=\"_blank\">";
		}
		
		// If company information is provided, display it.
		if ($this->company) {
			$html .= " ($this->company)";
		}
		
		// If a link is provided, close off the link.
		if ($this->link) {
			$html .= "</a>";
		}
	}
}
class Update {
	var $date;
	var $authors = array();
	var $reason;

	function add_author(&$author) {
		array_push($this->authors, $author);
	}
	
	// Render the article as html.
	function to_html(& $html) {
		$html .= "Updated ";
		$html .= date("F Y", $this->date);
		// Get the collection of authors and render them.
		$this->authors_to_html($this->authors, $html);
		if (strlen($this->reason)) {
			$html .= " $this->reason";
		}
	}

	// Render the article's authors to html.
	function authors_to_html($authors, & $html) {
		$count = count($authors);

		// If there is at least one author, print their information
		if ($count > 0) {
			$html .= ' by ';
			$html .= $authors[0]->to_html($html);
		}

		// Print each of the authors, excluding the first and the last
		for ($index = 1; $index < $count -1; $index ++) {
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
			$html .= $authors[$count -1]->to_html($html);
		}
	}
}

class Translation {
	var $language;
	var $date;
	var $authors = array();
	var $root;
	var $link;

	function add_author(&$author) {
		array_push($this->authors, $author);
	}
	
	// Render the article as html.
	function to_html(& $html) {
		$image_file = "/flags/$this->language.gif";
		$html .= "<table border=\"0\"><tr><td><a target=\"_blank\" href=\"$this->root/$this->link\"><img src=\"$image_file\" align=\"left\" alt=\"[$this->language]\"></a></td>";
		$html .= "<td>This article is available in ";
		switch ($this->language) {
			case "cn" : $html .= "Chinese"; break;
			case "de" : $html .= "German"; break;
			case "fr" : $html .= "French"; break;
			case "en" : $html .= "English"; break;
			case "pdf" : $html .= "Adobe Acrobat (PDF)"; break;
			default : $html .= "[$this->language]"; break;
		}
//		$html .= date("F Y", $this->date);
		// Get the collection of authors and render them.
		$this->authors_to_html($this->authors, $html);
		$html .= "</td></tr></table>";
	}

	// Render the article's authors to html.
	function authors_to_html($authors, & $html) {
		$count = count($authors);

		// If there is at least one author, print their information
		if ($count > 0) {
			$html .= ' thanks to ';
			$html .= $authors[0]->to_html($html);
		}

		// Print each of the authors, excluding the first and the last
		for ($index = 1; $index < $count -1; $index ++) {
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
			$html .= $authors[$count -1]->to_html($html);
		}
	}
}
?>