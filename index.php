<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'

	#*****************************************************************************
	#
	# template.php
	#
	# Author: 		Denis Roy
	# Date:			2005-06-16
	#
	# Description: Type your page comments here - these are not sent to the browser
	#
	#
	#****************************************************************************
	
	#
	# Begin: page-specific settings.  Change these. 
	$pageTitle 		= "Eclipse Corner Articles";
	$pageKeywords	= "Type, page, keywords, here";
	$pageAuthor		= "Type your name here";
	
	# Add page-specific Nav bars here
	# Format is Link text, link URL (can be http://www.someothersite.com/), target (_self, _blank), level (1, 2 or 3)
	# $Nav->addNavSeparator("My Page Links", 	"downloads.php");
	# $Nav->addCustomNav("My Link", "mypage.php", "_self", 3);
	# $Nav->addCustomNav("Google", "http://www.google.com/", "_blank", 3);

	# End: page-specific settings
	#
	include("scripts/articles.php");
	$articles = articles_as_html();
//	include('articles2.php');
//	$articles=articles_as_html();
//	$articles=abstracts_to_html('articles.xml');
	# Paste your HTML content between the EOHTML markers!	
	$html = <<<EOHTML
	
<div id="midcolumn">
	<h1>$pageTitle</h1>
	<h2>Open Source Community</h2>

      <p>The following articles
        have been written by members of the development team and other members
        of the eclipse community. You too can contribute! Eclipse Corner depends
        on contributions from people like you.</p>
      <p>Interested in writing
          an article? See <a href="contributing.html">how
          to contribute an article</a>.</p>
	  <p>Besides these, a number of other web sites carry technical articles about
      Eclipse. You can find pointers to these on the <a href="../community/main.html#EclipseInformation">Eclipse
      Community page</a>.

 $articles
 </div>
EOHTML;

	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
