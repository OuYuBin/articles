<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'
/*******************************************************************************
 * Copyright (c) 2007 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Wayne Beaton (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

	#*****************************************************************************
	#
	# article.php
	#
	# Author: 		Wayne Beaton
	# Date:			2005-11-16
	#
	# Description: This page provides a frame for articles.
	#
	#
	#****************************************************************************
	
	$host = $_SERVER['HTTP_HOST'];
    $root = $_SERVER['DOCUMENT_ROOT'] . '/articles';
	$file = $_GET['file'];
	
	$article_path = "$root/$file";
	
	// If the requested article does not exist, redirect to a warning page.
	if (!file_exists($article_path)) {
		$file = "nosucharticle.html";
		$article_path = "$root/$file";
	}
	
	$info_file = dirname($article_path) . '/info.xml';
	if (file_exists($info_file)) {
		$info = simplexml_load_file($info_file);
	} else {
		$info = null;
	}
		
	#
	# Begin: page-specific settings.  Change these. 
	$pageTitle 		= "Eclipse Corner Articles";
	// TODO Should be able to extract the title from the XML data.
	$pageKeywords	= "article, articles, tutorial, tutorials, how-to, howto, whitepaper, whitepapers, white, paper";
	$pageAuthor		= "Wayne Beaton";
	
	# End: page-specific settings
	#
	$App->ExtraHtmlHeaders = "<link rel=\"stylesheet\" type=\"text/css\" href=\"layout.css\" media=\"screen\" />\n<base href=\"http://$host/articles/$file\"/>\n";

	$charset = $App->getHTTPParameter('charset');
	if ($charset) header("Content-Type: text/html; charset=$charset");
	
	ob_start();
?>
	<div style="float: left;">
		<a href="/articles/index.php"><img src="/articles/images/back.gif"/> Back to Eclipse Corner Articles</a>
	</div>
	<div style="float: right;">
		<a target="_blank" href="/articles/printable.php?file=<?= $file ?>"><img src="/articles/images/printer.gif"/> Printer-friendly version</a>
	</div>

	<div style="clear:both;"/>

	<?php include ("parts/notices.php"); ?>
	<?php include ("parts/versions.php"); ?>
 	
	<br/>
	<div class="article">
		<?php readfile($article_path); ?>		
	</div>

<?php
	$html = ob_get_contents();
	ob_end_clean();
	$App->generatePage($theme, $Menu, null, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>

<? 
function get_info_for_article($article_path) {
	
	
}

?>