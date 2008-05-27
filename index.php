<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'
/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
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
	# template.php
	#
	# Author: 		Wayne Beaton
	# Date:			2005-11-16
	#
	# Description: Landing page for articles.
	#
	#
	#****************************************************************************
	
	#
	# Begin: page-specific settings.  Change these. 
	$pageTitle 		= "Eclipse Corner Articles";
	$pageKeywords	= "article, articles, tutorial, tutorials, how-to, howto, whitepaper, whitepapers, white, paper";
	$pageAuthor		= "Wayne Beaton";
	
	# End: page-specific settings
	#
	//include("scripts/articles.php");
	require_once("../resources/scripts/resources.php");
	
	$resources = new Resources();
	$filter = new Filter();
	$filter->populate_from_html_request_header();
	$filter->type = 'article';
	
	$resources_list = $resources->get_resources($filter);
	
	$filter_summary = $filter->get_summary();
	
	$category_cloud = $resources->get_category_cloud($filter, 'article', 0);
	
	
	$count = count($resources_list);
	//get_categories_for_filtering_as_html('/articles/index.php', $filter);
	$rss = "<a href=\"/resources/resources.rss?type=article&title=Eclipse%20Corner%20Articles\"><img src=\"/images/rss2.gif\"></a>";
	$articles =  $resources->get_resources_table($resources_list, $filter, "$filter_summary ($count articles) $rss");
	//get_articles_as_html($filter);
	$resources->dispose();
	
	# Paste your HTML content between the EOHTML markers!	
	$html = <<<EOHTML
	
		<link rel="stylesheet" type="text/css" href="/resources/layout.css" media="screen" />
		<script language="javascript">
			function t(i, j) {
				var e = document.getElementById(i);
				var f = document.getElementById(j);
				var t = e.className;
				if (t.match('invisible')) { t = t.replace(/invisible/gi, 'visible'); }
				else { t = t.replace(/visible/gi, 'invisible'); }
				e.className = t;
				f.className = t;
			}
		</script>
<!--<div id="maincontent">-->
	<div id="midcolumn">
		<h1>$pageTitle</h1>
		<img src="images/articles.gif" align="right">
		<p>
			These following articles have been written by members of the 
			development team and other members of the eclipse community. 
			<i>All articles are listed here by date (most recent first).</i> You
			can filter the list by clicking a category filter to the right. 
		</p>
		<p>
			You too can contribute! Eclipse Corner depends on contributions 
			from people like you. Interested in writing an article? See <a href="contributing.php">how
       		to contribute an article</a>. If you are writing an article, please
       		consider leveraging our new Eclipse-based <a href="http://docbook.sourceforge.net/">DocBook</a>
       		authoring template. For more information, please see the Eclipse Corner
       		article <a href="Article-Authoring-With-Eclipse/AuthoringWithEclipse.html">Authoring with Eclipse</a>.
       	</p>
    	<p>
      		A number of other web sites carry technical articles about
			Eclipse. You can find pointers to these on the 
			<a href="/community/">Eclipse Community page</a>. You can find these articles
			and more on the <a href="/resources">Eclipse Resources</a> page.
		</p>
		<form action="http://www.google.com/cse" id="searchbox_017941334893793413703:sqfrdtd112s">
	 	<input name="cx" value="017941334893793413703:sqfrdtd112s" type="hidden">
	 	<input type="hidden" name="hq" value="inurl:eclipse.org/articles">
  		<input style="background: rgb(255, 255, 255) url(http://www.google.com/coop/intl/en/images/google_custom_search_watermark.gif) no-repeat scroll left center; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" name="q" size="25" type="text">
  		<input name="sa" value="Search" type="submit">
		</form>
		
 		<hr/>
 		$articles
	</div>
	<div id="rightcolumn">
		<div class="sideitem">
			<h6>Categories</h6>
			$category_cloud
		</div>
	</div>
	<div id="rightcolumn">
		<div class="sideitem">
			<h6>Eclipse Rich Client Platform Tutorial</h6>
			<ul>
			<li><a href="http://eclipse.org/articles/Article-RCP-1/tutorial1.html">Part 1</a></li>
			<li><a href="http://eclipse.org/articles/Article-RCP-2/tutorial2.html">Part 2</a></li>
			<li><a href="http://eclipse.org/articles/Article-RCP-3/tutorial3.html">Part 3</a></li>
			</ul>
		</div>
	</div>
	<div id="rightcolumn">
		<div class="sideitem">
			<h6>Other Tutorials</h6>
			<ul>
			<li><a href="http://www.eclipse.org/webtools/jst/components/ws/1.5/tutorials/index.html">Web Service Tutorials for WTP 1.5</a></li>
			</ul>
		</div>
	</div>
	<div id="rightcolumn">
		<div class="sideitem">
			<h6>Other Sources of Eclipse Information</h6>
			<ul>
			<li><a href="http://dev2dev.bea.com/eclipse/" target="_blank">dev2dev Online</a></li>
			<li><a href="http://www.ibm.com/developer" target="_blank">developerWorks</a></li>
			<li><a href="http://eclipse.sys-con.com/" target="_blank">Eclipse Developer's Journal</a></li>
			<li><a href="http://www.eclipse-magazin.de/" target="_blank">Eclipse Magazin</a></li>
			<li><a href="http://www.eclipsemag.net/" target="_blank">Eclipse Magazine</a></li>
			<li><a href="http://eclipse.techforge.com/" target="_blank">Eclipse TechForge</a></li>
			<li><a href="http://www.eclipsesource.com/" target="_blank">Eclipse Source</a></li>
			<li><a href="http://www.eclipsezone.com/" target="_blank">Eclipse Zone</a></li>
			</ul>
		</div>
	</div>
<!--</div>-->
<script language="javascript">
<!--
	if (top.location != location) {
		top.location.href = document.location.href ;
	}

-->
</script>
EOHTML;

	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
