<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'

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
	include("scripts/articles.php");
	$filter = $_GET['filter'];
	if (!$filter) $filter = 'all';
	$categories = get_categories_for_filtering_as_html('/articles/index.php', $filter);
	$articles = get_articles_as_html($filter);

	# Paste your HTML content between the EOHTML markers!	
	$html = <<<EOHTML
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
      		Besides these, a number of other web sites carry technical articles about
			Eclipse. You can find pointers to these on the 
			<a href="/community/">Eclipse Community page</a>.
		</p>
		
		<p>
			<strong>New!</strong> RSS feed for recent articles <a href="/articles/articles.rss?filter=recent"><img src="/images/rss.gif" title="RSS feed for Most Recent Articles" alt="[RSS]"></a>
			(added or updated in the past year).
		</p>
		
		<form method="get" action="/search/search.cgi">
        	<input type="hidden" name="ul" value="articles" />
			<input type="hidden" name="wf" value="574a74"; />
			Search Articles: <input type="text" name="q" value="" />
			<input type="submit" value="Search!" />
 		</form>
		<div class="homeitem3col">
			$articles
		</div>
	</div>
	<div id="rightcolumn">
		<div class="sideitem">
			<h6>Filter</h6>
			<ul>
			$categories
			</ul>
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
			<li><a href="/webtools/jst/components/ws/1.5M6/tutorials/index.html">Web Service Tutorials for WTP 1.5</a></li>
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
