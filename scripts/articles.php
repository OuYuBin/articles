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
include ("articles_xml.php");

class ArticleListing {
	var $categories = array ();

	function add_category(& $category) {
		$id = $category->id;
		$this->categories = array_merge($this->categories, array ($id => $category));
	}

	function add_article(& $article) {
		foreach ($article->categories as $category_id) {
			$category = & $this->categories[$category_id];
			if (is_object($category)) {
				$category->add_article($article);
			}
		}

		$this->add_recent_article($article);
		$this->categories["general"]->add_article($article);
	}

	function add_recent_article(& $article) {
		// If there is no "recent" category, bail out.
		$category = $this->categories["recent"];
		if (!is_object(category))
			return;

		// I consider recent to mean changed within the
		// last six months...
		$base_date = strtotime("-6 month");
		if (($article->date > $base_date) or ($article->update > $base_date)) {
			$this->categories["recent"]->add_article($article);
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
		if (!$this->has_articles())
			return;
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
	function add_article(& $article) {
		array_push($this->articles, $article);
	}

	// Does this category have any articles?
	function has_articles() {
		return count($this->articles) > 0;
	}
}

class Article {
	var $title;
	var $link;
	var $abstract;
	var $authors;
	var $categories;
	var $date;
	var $updates;

	// Render the article as html.
	function to_html(& $html) {
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
	function authors_to_html($authors) {
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
class Update {
	var $date;
	var $authors;
	var $reason;

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
?>

